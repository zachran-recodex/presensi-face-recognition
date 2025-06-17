<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FaceRecognitionService
{
    private string $baseUrl;

    private string $accessToken;

    private string $faceGalleryId;

    public function __construct()
    {
        $this->baseUrl = config('services.biznet_face.base_url', 'https://fr.neoapi.id/risetai/face-api');
        $this->accessToken = config('services.biznet_face.access_token');
        $this->faceGalleryId = config('services.biznet_face.face_gallery_id', 'attendance_system');
    }

    /**
     * Get API counters/remaining quota
     */
    public function getCounters(): array
    {
        try {
            // The API expects GET requests with JSON body (unusual but required)
            $response = Http::timeout(30)->withHeaders([
                'Accesstoken' => $this->accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->withBody(json_encode(['trx_id' => $this->generateTrxId()]), 'application/json')
                ->get("{$this->baseUrl}/client/get-counters");

            if ($response->successful()) {
                $result = $response->json();
                // Check if the response has the correct format
                if (isset($result['risetai'])) {
                    return $result['risetai'];
                }

                return $result;
            }

            throw new \Exception('Failed to get counters: '.$response->body());
        } catch (\Exception $e) {
            Log::error('Face API get counters error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Create face gallery if not exists
     */
    public function createFaceGallery(): array
    {
        try {
            $payload = json_encode([
                'facegallery_id' => $this->faceGalleryId,
                'trx_id' => $this->generateTrxId(),
            ]);

            $response = Http::timeout(30)->withHeaders([
                'Accesstoken' => $this->accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->withBody($payload, 'application/json')
                ->post("{$this->baseUrl}/facegallery/create-facegallery");

            if ($response->successful()) {
                $result = $response->json();
                // Check if the response has the correct format
                if (isset($result['risetai'])) {
                    return $result['risetai'];
                }

                return $result;
            }

            // If gallery already exists, that's okay
            $responseData = $response->json();
            if (isset($responseData['risetai']['status']) &&
                ($responseData['risetai']['status'] == '400' || $responseData['risetai']['status'] == '417')) {
                return ['status' => '200', 'message' => 'Gallery already exists'];
            }

            throw new \Exception('Failed to create face gallery: '.$response->body());
        } catch (\Exception $e) {
            Log::error('Face API create gallery error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Enroll user face to the gallery
     */
    public function enrollFace(string $userId, string $userName, string $base64Image): array
    {
        try {
            $payload = json_encode([
                'user_id' => $userId,
                'user_name' => $userName,
                'facegallery_id' => $this->faceGalleryId,
                'image' => $this->processBase64Image($base64Image),
                'trx_id' => $this->generateTrxId(),
            ]);

            $response = Http::timeout(60)->withHeaders([
                'Accesstoken' => $this->accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->withBody($payload, 'application/json')
                ->post("{$this->baseUrl}/facegallery/enroll-face");

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['risetai'])) {
                    return $result['risetai'];
                }

                return $result;
            }

            throw new \Exception('Failed to enroll face: '.$response->body());
        } catch (\Exception $e) {
            Log::error('Face API enroll error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify face against enrolled user
     */
    public function verifyFace(string $userId, string $base64Image): array
    {
        try {
            $payload = json_encode([
                'user_id' => $userId,
                'facegallery_id' => $this->faceGalleryId,
                'image' => $this->processBase64Image($base64Image),
                'trx_id' => $this->generateTrxId(),
            ]);

            $response = Http::timeout(60)->withHeaders([
                'Accesstoken' => $this->accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->withBody($payload, 'application/json')
                ->post("{$this->baseUrl}/facegallery/verify-face");

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['risetai'])) {
                    return $result['risetai'];
                }

                return $result;
            }

            throw new \Exception('Failed to verify face: '.$response->body());
        } catch (\Exception $e) {
            Log::error('Face API verify error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Identify face from gallery (1:N authentication)
     */
    public function identifyFace(string $base64Image): array
    {
        try {
            $response = Http::timeout(60)->withHeaders([
                'Accesstoken' => $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/facegallery/identify-face", [
                'facegallery_id' => $this->faceGalleryId,
                'image' => $base64Image,
                'trx_id' => $this->generateTrxId(),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to identify face: '.$response->body());
        } catch (\Exception $e) {
            Log::error('Face API identify error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete enrolled face
     */
    public function deleteFace(string $userId): array
    {
        try {
            $response = Http::withHeaders([
                'Accesstoken' => $this->accessToken,
                'Content-Type' => 'application/json',
            ])->delete("{$this->baseUrl}/facegallery/delete-face", [
                'user_id' => $userId,
                'facegallery_id' => $this->faceGalleryId,
                'trx_id' => $this->generateTrxId(),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to delete face: '.$response->body());
        } catch (\Exception $e) {
            Log::error('Face API delete error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Get list of enrolled faces
     */
    public function listFaces(): array
    {
        try {
            // Use GET with JSON body like the counters endpoint
            $response = Http::timeout(30)->withHeaders([
                'Accesstoken' => $this->accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->withBody(json_encode([
                'facegallery_id' => $this->faceGalleryId,
                'trx_id' => $this->generateTrxId(),
            ]), 'application/json')
                ->get("{$this->baseUrl}/facegallery/list-faces");

            if ($response->successful()) {
                $result = $response->json();
                // Check if the response has the correct format
                if (isset($result['risetai'])) {
                    return $result['risetai'];
                }

                return $result;
            }

            throw new \Exception('Failed to get face list: '.$response->body());
        } catch (\Exception $e) {
            Log::error('Face API list faces error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Compare two images
     */
    public function compareImages(string $sourceImage, string $targetImage): array
    {
        try {
            $response = Http::withHeaders([
                'Accesstoken' => $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/compare-images", [
                'source_image' => $sourceImage,
                'target_image' => $targetImage,
                'trx_id' => $this->generateTrxId(),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to compare images: '.$response->body());
        } catch (\Exception $e) {
            Log::error('Face API compare error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate unique transaction ID
     */
    private function generateTrxId(): string
    {
        return 'trx_'.time().'_'.rand(1000, 9999);
    }

    /**
     * Process base64 image (remove data:image prefix if exists)
     */
    public function processBase64Image(string $base64Image): string
    {
        // Remove data:image/jpeg;base64, or data:image/png;base64, prefix if exists
        if (strpos($base64Image, 'data:image') === 0) {
            return substr($base64Image, strpos($base64Image, ',') + 1);
        }

        return $base64Image;
    }
}
