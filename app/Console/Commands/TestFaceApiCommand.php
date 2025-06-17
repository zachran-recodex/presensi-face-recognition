<?php

namespace App\Console\Commands;

use App\Services\FaceRecognitionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestFaceApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'face:test-api
                            {--check-connection : Test API connection}
                            {--get-counters : Get API usage counters}
                            {--create-gallery : Create face gallery}
                            {--list-faces : List enrolled faces}
                            {--all : Run all tests}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Face Recognition API connectivity and functionality';

    /**
     * Execute the console command.
     */
    public function handle(FaceRecognitionService $faceService): int
    {
        $this->info('🔍 Face Recognition API Testing Tool');
        $this->info('=====================================');

        // Check if API credentials are configured
        if (! config('services.biznet_face.access_token')) {
            $this->error('❌ Face API access token is not configured in .env file');
            $this->info('Please set BIZNET_FACE_ACCESS_TOKEN in your .env file');

            return self::FAILURE;
        }

        if ($this->option('all')) {
            $this->runAllTests($faceService);
        } elseif ($this->option('check-connection')) {
            $this->testConnection($faceService);
        } elseif ($this->option('get-counters')) {
            $this->getCounters($faceService);
        } elseif ($this->option('create-gallery')) {
            $this->createGallery($faceService);
        } elseif ($this->option('list-faces')) {
            $this->listFaces($faceService);
        } else {
            $this->info('Please specify an option. Use --help for available options.');

            return self::INVALID;
        }

        return self::SUCCESS;
    }

    private function runAllTests(FaceRecognitionService $faceService): void
    {
        $this->info('🚀 Running comprehensive API tests...');
        $this->newLine();

        $this->testConnection($faceService);
        $this->newLine();

        $this->getCounters($faceService);
        $this->newLine();

        $this->createGallery($faceService);
        $this->newLine();

        $this->listFaces($faceService);
        $this->newLine();

        $this->info('✅ All tests completed!');
    }

    private function testConnection(FaceRecognitionService $faceService): void
    {
        $this->info('🔗 Testing API Connection...');

        // Show current configuration
        $baseUrl = config('services.biznet_face.base_url');
        $accessToken = config('services.biznet_face.access_token');
        $galleryId = config('services.biznet_face.face_gallery_id');

        $this->info('   Base URL: '.$baseUrl);
        $this->info('   Access Token: '.($accessToken ? substr($accessToken, 0, 10).'...' : 'NOT SET'));
        $this->info('   Gallery ID: '.$galleryId);

        try {
            $result = $faceService->getCounters();

            if (isset($result['status']) && $result['status'] == '200') {
                $this->info('✅ API connection successful!');
                $this->info('   Status: '.$result['status']);
                $this->info('   Message: '.($result['status_message'] ?? 'Success'));
            } else {
                $this->error('❌ API connection failed');
                $this->error('   Status: '.($result['status'] ?? 'Unknown'));
                $this->error('   Message: '.($result['status_message'] ?? 'Unknown error'));
                $this->error('   Full Response: '.json_encode($result));
            }
        } catch (\Exception $e) {
            $this->error('❌ Connection test failed: '.$e->getMessage());
            $this->error('   Exception class: '.get_class($e));

            // Let's try a manual curl-like test
            $this->info('🔧 Trying manual curl test...');
            $this->testWithCurl();
        }
    }

    private function getCounters(FaceRecognitionService $faceService): void
    {
        $this->info('📊 Getting API Usage Counters...');

        try {
            $result = $faceService->getCounters();

            if (isset($result['status']) && $result['status'] == '200') {
                $this->info('✅ Counters retrieved successfully!');

                if (isset($result['remaining_limit'])) {
                    $limits = $result['remaining_limit'];
                    $this->table(
                        ['Resource', 'Remaining'],
                        [
                            ['API Hits', $limits['n_api_hits'] ?? 'N/A'],
                            ['Face Enrollments', $limits['n_face'] ?? 'N/A'],
                            ['Face Galleries', $limits['n_facegallery'] ?? 'N/A'],
                        ]
                    );
                }
            } else {
                $this->error('❌ Failed to get counters');
                $this->error('   Status: '.($result['status'] ?? 'Unknown'));
                $this->error('   Message: '.($result['status_message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            $this->error('❌ Failed to get counters: '.$e->getMessage());
        }
    }

    private function createGallery(FaceRecognitionService $faceService): void
    {
        $galleryId = config('services.biznet_face.face_gallery_id');
        $this->info("🏛️  Creating/Checking Face Gallery: {$galleryId}");

        try {
            $result = $faceService->createFaceGallery();

            if (isset($result['status']) && ($result['status'] == '200' || strpos($result['message'] ?? '', 'already exists') !== false)) {
                $this->info('✅ Face gallery is ready!');
                $this->info('   Gallery ID: '.$galleryId);
                $this->info('   Status: '.($result['status'] ?? 'Unknown'));
                if (isset($result['message'])) {
                    $this->info('   Message: '.$result['message']);
                }
            } else {
                $this->warn('⚠️  Gallery creation response:');
                $this->info('   Status: '.($result['status'] ?? 'Unknown'));
                $this->info('   Message: '.($result['status_message'] ?? 'Unknown error'));

                // Status 417 means gallery already exists, which is fine
                if (isset($result['status']) && $result['status'] == '417') {
                    $this->info('✅ Face gallery already exists (which is fine)');
                }
            }
        } catch (\Exception $e) {
            $this->error('❌ Gallery creation failed: '.$e->getMessage());
        }
    }

    private function listFaces(FaceRecognitionService $faceService): void
    {
        $this->info('👥 Listing Enrolled Faces...');

        try {
            $result = $faceService->listFaces();

            if (isset($result['status']) && $result['status'] == '200') {
                $this->info('✅ Face list retrieved successfully!');

                if (isset($result['faces']) && is_array($result['faces'])) {
                    $faces = $result['faces'];

                    if (empty($faces)) {
                        $this->warn('⚠️  No faces enrolled in the gallery yet');
                        $this->info('   Users need to enroll their faces through the application');
                    } else {
                        $this->info('   Found '.count($faces).' enrolled face(s):');

                        $tableData = [];
                        foreach ($faces as $face) {
                            $tableData[] = [
                                'User ID' => $face['user_id'] ?? 'N/A',
                                'User Name' => $face['user_name'] ?? 'N/A',
                            ];
                        }

                        $this->table(['User ID', 'User Name'], $tableData);
                    }
                } else {
                    $this->warn('⚠️  Unexpected response format for face list');
                }
            } else {
                // Status 418 means gallery is empty, which is fine for a new setup
                if (isset($result['status']) && $result['status'] == '418') {
                    $this->info('✅ Gallery is empty (no faces enrolled yet)');
                    $this->info('   This is normal for a new installation');
                    $this->info('   Users can enroll their faces through the web interface');
                } else {
                    $this->error('❌ Failed to list faces');
                    $this->error('   Status: '.($result['status'] ?? 'Unknown'));
                    $this->error('   Message: '.($result['status_message'] ?? 'Unknown error'));
                }
            }
        } catch (\Exception $e) {
            $this->error('❌ Face listing failed: '.$e->getMessage());
        }
    }

    private function testWithCurl(): void
    {
        $baseUrl = config('services.biznet_face.base_url');
        $accessToken = config('services.biznet_face.access_token');
        $trxId = 'trx_'.time().'_'.rand(1000, 9999);

        $this->info('   Testing different approaches...');

        // Test 1: Simple GET
        try {
            $this->info('   1. Simple GET:');
            $response = Http::timeout(30)->get("{$baseUrl}/client/get-counters");
            $this->info('   Status: '.$response->status().' | Body: '.substr($response->body(), 0, 100));
        } catch (\Exception $e) {
            $this->error('   GET test failed: '.$e->getMessage());
        }

        // Test 2: GET with token header
        try {
            $this->info('   2. GET with Accesstoken header:');
            $response = Http::timeout(30)->withHeaders([
                'Accesstoken' => $accessToken,
            ])->get("{$baseUrl}/client/get-counters");
            $this->info('   Status: '.$response->status().' | Body: '.substr($response->body(), 0, 100));
        } catch (\Exception $e) {
            $this->error('   GET with token test failed: '.$e->getMessage());
        }

        // Test 3: Try GET with empty JSON body (unusual but some APIs need this)
        try {
            $this->info('   3. GET with empty JSON body:');
            $response = Http::timeout(30)->withHeaders([
                'Accesstoken' => $accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->withBody('{}', 'application/json')
                ->get("{$baseUrl}/client/get-counters");
            $this->info('   Status: '.$response->status().' | Body: '.substr($response->body(), 0, 200));
        } catch (\Exception $e) {
            $this->error('   GET with empty JSON test failed: '.$e->getMessage());
        }

        // Test 4: Try GET with trx_id in JSON body
        try {
            $this->info('   4. GET with JSON body including trx_id:');
            $response = Http::timeout(30)->withHeaders([
                'Accesstoken' => $accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->withBody(json_encode(['trx_id' => $trxId]), 'application/json')
                ->get("{$baseUrl}/client/get-counters");
            $this->info('   Status: '.$response->status().' | Body: '.substr($response->body(), 0, 200));
        } catch (\Exception $e) {
            $this->error('   GET with JSON trx_id test failed: '.$e->getMessage());
        }
    }
}
