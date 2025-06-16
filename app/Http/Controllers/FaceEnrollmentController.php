<?php

namespace App\Http\Controllers;

use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FaceEnrollmentController extends Controller
{
    private FaceRecognitionService $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    /**
     * Show face enrollment form
     */
    public function create(): View
    {
        $user = auth()->user();

        if ($user->is_face_enrolled) {
            return redirect()->route('dashboard')
                ->with('status', 'Your face is already enrolled. You can update it below if needed.');
        }

        return view('face-enrollment.create');
    }

    /**
     * Process face enrollment
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            $validated = $request->validate([
                'face_image' => 'required|string', // base64 image
            ]);

            // Process face image
            $base64Image = $this->faceService->processBase64Image($validated['face_image']);

            // Create face gallery if not exists
            $this->faceService->createFaceGallery();

            // Enroll face
            $userId = $user->employee_id ?: $user->id;
            $enrollmentResult = $this->faceService->enrollFace(
                $userId,
                $user->name,
                $base64Image
            );

            if ($enrollmentResult['status'] !== '200') {
                return response()->json([
                    'success' => false,
                    'message' => 'Face enrollment failed: ' . ($enrollmentResult['status_message'] ?? 'Unknown error')
                ], 422);
            }

            // Update user record
            $user->update([
                'face_image' => $base64Image,
                'is_face_enrolled' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Face enrolled successfully! You can now use face recognition for attendance.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Face enrollment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show face update form
     */
    public function edit(): View
    {
        $user = auth()->user();

        if (!$user->is_face_enrolled) {
            return redirect()->route('face.enroll')
                ->withErrors(['error' => 'Please enroll your face first']);
        }

        return view('face-enrollment.edit');
    }

    /**
     * Update enrolled face
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user->is_face_enrolled) {
                return response()->json([
                    'success' => false,
                    'message' => 'No enrolled face found to update'
                ], 422);
            }

            $validated = $request->validate([
                'face_image' => 'required|string', // base64 image
            ]);

            // Process face image
            $base64Image = $this->faceService->processBase64Image($validated['face_image']);

            // Delete existing face first
            $userId = $user->employee_id ?: $user->id;
            $this->faceService->deleteFace($userId);

            // Enroll new face
            $enrollmentResult = $this->faceService->enrollFace(
                $userId,
                $user->name,
                $base64Image
            );

            if ($enrollmentResult['status'] !== '200') {
                return response()->json([
                    'success' => false,
                    'message' => 'Face update failed: ' . ($enrollmentResult['status_message'] ?? 'Unknown error')
                ], 422);
            }

            // Update user record
            $user->update([
                'face_image' => $base64Image
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Face updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Face update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete enrolled face
     */
    public function destroy(): RedirectResponse
    {
        try {
            $user = auth()->user();

            if (!$user->is_face_enrolled) {
                return redirect()->route('dashboard')
                    ->withErrors(['error' => 'No enrolled face found to delete']);
            }

            // Delete from face recognition service
            $userId = $user->employee_id ?: $user->id;
            $this->faceService->deleteFace($userId);

            // Update user record
            $user->update([
                'face_image' => null,
                'is_face_enrolled' => false
            ]);

            return redirect()->route('dashboard')
                ->with('status', 'Face enrollment deleted successfully');

        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Failed to delete face enrollment: ' . $e->getMessage()]);
        }
    }

    /**
     * Test face verification
     */
    public function testVerification(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user->is_face_enrolled) {
                return response()->json([
                    'success' => false,
                    'message' => 'No enrolled face found for verification'
                ], 422);
            }

            $validated = $request->validate([
                'face_image' => 'required|string', // base64 image
            ]);

            // Process face image
            $base64Image = $this->faceService->processBase64Image($validated['face_image']);

            // Verify face
            $userId = $user->employee_id ?: $user->id;
            $verificationResult = $this->faceService->verifyFace($userId, $base64Image);

            if ($verificationResult['status'] !== '200') {
                return response()->json([
                    'success' => false,
                    'message' => 'Face verification failed: ' . ($verificationResult['status_message'] ?? 'Unknown error')
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Face verification completed',
                'data' => [
                    'verified' => $verificationResult['verified'] ?? false,
                    'similarity' => $verificationResult['similarity'] ?? 0,
                    'masker' => $verificationResult['masker'] ?? false,
                    'user_name' => $verificationResult['user_name'] ?? $user->name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Face verification failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
