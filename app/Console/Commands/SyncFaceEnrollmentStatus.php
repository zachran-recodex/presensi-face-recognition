<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\FaceRecognitionService;
use Illuminate\Console\Command;

class SyncFaceEnrollmentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'face:sync-enrollment-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync face enrollment status between database and Face API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting face enrollment status synchronization...');

        $faceService = app(FaceRecognitionService::class);
        
        try {
            // Get all faces from API
            $faceList = $faceService->listFaces();
            $apiUserIds = [];
            
            if (isset($faceList['faces'])) {
                foreach ($faceList['faces'] as $face) {
                    $apiUserIds[] = $face['user_id'];
                }
            }
            
            $this->info('Found ' . count($apiUserIds) . ' faces in API: ' . implode(', ', $apiUserIds));
            
            // Get all users from database
            $users = User::all();
            $syncedCount = 0;
            
            foreach ($users as $user) {
                $userId = (string) ($user->employee_id ?: $user->id);
                $isInAPI = in_array($userId, $apiUserIds);
                
                if ($isInAPI && !$user->is_face_enrolled) {
                    // User is enrolled in API but not in database
                    $user->update(['is_face_enrolled' => true]);
                    $this->info("✓ Marked user {$user->name} ({$userId}) as enrolled");
                    $syncedCount++;
                } elseif (!$isInAPI && $user->is_face_enrolled) {
                    // User is enrolled in database but not in API
                    $user->update(['is_face_enrolled' => false]);
                    $this->info("✓ Marked user {$user->name} ({$userId}) as not enrolled");
                    $syncedCount++;
                }
            }
            
            $this->info("Synchronization completed. Updated {$syncedCount} users.");
            
        } catch (\Exception $e) {
            $this->error('Error during synchronization: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}