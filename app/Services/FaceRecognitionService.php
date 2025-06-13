<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FaceRecognitionService
{
    private string $baseUrl;
    private string $accessToken;
    private string $defaultGalleryId;

    public function __construct()
    {
        $this->baseUrl = config('services.face_recognition.base_url', 'https://fr.neoapi.id/risetai/face-api');
        $this->accessToken = config('services.face_recognition.access_token');
        $this->defaultGalleryId = config('services.face_recognition.gallery_id', 'attendance_system');
    }

    public function createFaceGallery(string $galleryId = null): array
    {
        $galleryId = $galleryId ?? $this->defaultGalleryId;

        $response = Http::withHeaders([
            'Accesstoken' => $this->accessToken,
        ])->post("{$this->baseUrl}/facegallery/create-facegallery", [
            'facegallery_id' => $galleryId,
            'trx_id' => $this->generateTrxId(),
        ]);

        return $this->handleResponse($response);
    }

    public function enrollFace(string $employeeId, string $employeeName, string $base64Image, string $galleryId = null): array
    {
        $galleryId = $galleryId ?? $this->defaultGalleryId;

        $response = Http::withHeaders([
            'Accesstoken' => $this->accessToken,
        ])->post("{$this->baseUrl}/facegallery/enroll-face", [
            'user_id' => $employeeId,
            'user_name' => $employeeName,
            'facegallery_id' => $galleryId,
            'image' => $base64Image,
            'trx_id' => $this->generateTrxId(),
        ]);

        return $this->handleResponse($response);
    }

    public function verifyFace(string $employeeId, string $base64Image, string $galleryId = null): array
    {
        $galleryId = $galleryId ?? $this->defaultGalleryId;

        $response = Http::withHeaders([
            'Accesstoken' => $this->accessToken,
        ])->post("{$this->baseUrl}/facegallery/verify-face", [
            'user_id' => $employeeId,
            'facegallery_id' => $galleryId,
            'image' => $base64Image,
            'trx_id' => $this->generateTrxId(),
        ]);

        return $this->handleResponse($response);
    }

    public function identifyFace(string $base64Image, string $galleryId = null): array
    {
        $galleryId = $galleryId ?? $this->defaultGalleryId;

        $response = Http::withHeaders([
            'Accesstoken' => $this->accessToken,
        ])->post("{$this->baseUrl}/facegallery/identify-face", [
            'facegallery_id' => $galleryId,
            'image' => $base64Image,
            'trx_id' => $this->generateTrxId(),
        ]);

        return $this->handleResponse($response);
    }

    public function deleteFace(string $employeeId, string $galleryId = null): array
    {
        $galleryId = $galleryId ?? $this->defaultGalleryId;

        $response = Http::withHeaders([
            'Accesstoken' => $this->accessToken,
        ])->delete("{$this->baseUrl}/facegallery/delete-face", [
            'user_id' => $employeeId,
            'facegallery_id' => $galleryId,
            'trx_id' => $this->generateTrxId(),
        ]);

        return $this->handleResponse($response);
    }

    public function listFaces(string $galleryId = null): array
    {
        $galleryId = $galleryId ?? $this->defaultGalleryId;

        $response = Http::withHeaders([
            'Accesstoken' => $this->accessToken,
        ])->get("{$this->baseUrl}/facegallery/list-faces", [
            'facegallery_id' => $galleryId,
            'trx_id' => $this->generateTrxId(),
        ]);

        return $this->handleResponse($response);
    }

    public function getCounters(): array
    {
        $response = Http::withHeaders([
            'Accesstoken' => $this->accessToken,
        ])->get("{$this->baseUrl}/client/get-counters", [
            'trx_id' => $this->generateTrxId(),
        ]);

        return $this->handleResponse($response);
    }

    public function compareImages(string $sourceImage, string $targetImage): array
    {
        $response = Http::withHeaders([
            'Accesstoken' => $this->accessToken,
        ])->post("{$this->baseUrl}/compare-images", [
            'source_image' => $sourceImage,
            'target_image' => $targetImage,
            'trx_id' => $this->generateTrxId(),
        ]);

        return $this->handleResponse($response);
    }

    private function handleResponse($response): array
    {
        $data = $response->json();

        Log::info('Face Recognition API Response', [
            'status_code' => $response->status(),
            'response' => $data,
        ]);

        if (!$response->successful()) {
            throw new \Exception("Face Recognition API Error: " . ($data['status_message'] ?? 'Unknown error'));
        }

        return $data;
    }

    private function generateTrxId(): string
    {
        return 'trx_' . uniqid() . '_' . time();
    }

    public function isVerificationSuccessful(array $verifyResponse, float $threshold = 0.75): bool
    {
        return isset($verifyResponse['verified']) &&
               $verifyResponse['verified'] === true &&
               isset($verifyResponse['similarity']) &&
               $verifyResponse['similarity'] >= $threshold;
    }
}
