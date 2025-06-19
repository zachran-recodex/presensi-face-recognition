<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FaceRecognitionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaceApiTestController extends Controller
{
    private FaceRecognitionService $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    /**
     * Show Face API testing interface
     */
    public function index(): View
    {
        // Get API configuration
        $config = [
            'base_url' => config('services.biznet_face.base_url'),
            'access_token' => config('services.biznet_face.access_token') ?
                substr(config('services.biznet_face.access_token'), 0, 10).'...' : 'NOT SET',
            'gallery_id' => config('services.biznet_face.face_gallery_id'),
        ];

        // Get enrolled users for testing
        $enrolledUsers = User::where('is_face_enrolled', true)
            ->select('id', 'name', 'employee_id', 'email')
            ->get();

        return view('admin.face-api-test.index', compact('config', 'enrolledUsers'));
    }

    /**
     * Test API connection and get counters
     */
    public function testConnection(): JsonResponse
    {
        try {
            $result = $this->faceService->getCounters();

            return response()->json([
                'success' => true,
                'message' => 'API connection test completed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'API connection failed: '.$e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get API usage counters
     */
    public function getCounters(): JsonResponse
    {
        try {
            $result = $this->faceService->getCounters();

            return response()->json([
                'success' => true,
                'message' => 'Counters retrieved successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get counters: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create face gallery
     */
    public function createGallery(): JsonResponse
    {
        try {
            $result = $this->faceService->createFaceGallery();

            return response()->json([
                'success' => true,
                'message' => 'Gallery creation completed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create gallery: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get my galleries
     */
    public function getMyGalleries(): JsonResponse
    {
        try {
            $result = $this->faceService->getMyGalleries();

            return response()->json([
                'success' => true,
                'message' => 'My galleries retrieved successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get my galleries: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * List all enrolled faces
     */
    public function listFaces(): JsonResponse
    {
        try {
            $result = $this->faceService->listFaces();

            return response()->json([
                'success' => true,
                'message' => 'Face list retrieved successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to list faces: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test face enrollment
     */
    public function testEnrollment(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|string',
                'user_name' => 'required|string',
                'face_image' => 'required|string', // base64 image
            ]);

            $base64Image = $this->faceService->processBase64Image($validated['face_image']);

            $result = $this->faceService->enrollFace(
                $validated['user_id'],
                $validated['user_name'],
                $base64Image
            );

            return response()->json([
                'success' => true,
                'message' => 'Face enrollment test completed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Face enrollment test failed: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test face verification
     */
    public function testVerification(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|string',
                'face_image' => 'required|string', // base64 image
            ]);

            $base64Image = $this->faceService->processBase64Image($validated['face_image']);

            $result = $this->faceService->verifyFace(
                $validated['user_id'],
                $base64Image
            );

            return response()->json([
                'success' => true,
                'message' => 'Face verification test completed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Face verification test failed: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test face identification
     */
    public function testIdentification(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'face_image' => 'required|string', // base64 image
            ]);

            $base64Image = $this->faceService->processBase64Image($validated['face_image']);

            $result = $this->faceService->identifyFace($base64Image);

            return response()->json([
                'success' => true,
                'message' => 'Face identification test completed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Face identification test failed: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test face comparison
     */
    public function testComparison(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'source_image' => 'required|string', // base64 image
                'target_image' => 'required|string', // base64 image
            ]);

            $sourceImage = $this->faceService->processBase64Image($validated['source_image']);
            $targetImage = $this->faceService->processBase64Image($validated['target_image']);

            $result = $this->faceService->compareImages($sourceImage, $targetImage);

            return response()->json([
                'success' => true,
                'message' => 'Face comparison test completed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Face comparison test failed: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test face deletion
     */
    public function testDeletion(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|string',
            ]);

            $result = $this->faceService->deleteFace($validated['user_id']);

            return response()->json([
                'success' => true,
                'message' => 'Face deletion test completed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Face deletion test failed: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update Face Gallery ID
     */
    public function updateGalleryId(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'gallery_id' => 'required|string|max:255',
            ]);

            // Read current .env file
            $envPath = base_path('.env');
            if (! file_exists($envPath)) {
                return response()->json([
                    'success' => false,
                    'message' => '.env file not found',
                ], 500);
            }

            $envContent = file_get_contents($envPath);

            // Update or add BIZNET_FACE_GALLERY_ID
            if (preg_match('/^BIZNET_FACE_GALLERY_ID=.*$/m', $envContent)) {
                $envContent = preg_replace(
                    '/^BIZNET_FACE_GALLERY_ID=.*$/m',
                    'BIZNET_FACE_GALLERY_ID='.$validated['gallery_id'],
                    $envContent
                );
            } else {
                $envContent .= "\nBIZNET_FACE_GALLERY_ID=".$validated['gallery_id'];
            }

            // Write back to .env file
            file_put_contents($envPath, $envContent);

            // Clear config cache to reflect changes
            if (app()->environment('production')) {
                \Artisan::call('config:cache');
            } else {
                \Artisan::call('config:clear');
            }

            return response()->json([
                'success' => true,
                'message' => 'Face Gallery ID updated successfully',
                'data' => [
                    'new_gallery_id' => $validated['gallery_id'],
                    'previous_gallery_id' => config('services.biznet_face.face_gallery_id'),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Face Gallery ID: '.$e->getMessage(),
            ], 500);
        }
    }
}
