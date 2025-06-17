<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Location;
use App\Models\User;
use App\Services\FaceRecognitionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    private FaceRecognitionService $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    /**
     * Display attendance dashboard for users
     */
    public function index(): View
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminIndex();
        }

        $locations = Location::active()->get();
        $todayAttendances = $user->getTodayAttendances();
        $hasCheckedIn = $user->hasCheckedInToday();
        $hasCheckedOut = $user->hasCheckedOutToday();

        // Get this month's attendance summary
        $thisMonthAttendances = $user->attendances()
            ->thisMonth()
            ->with('location')
            ->orderBy('attendance_time', 'desc')
            ->get();

        return view('attendance.index', compact(
            'locations',
            'todayAttendances',
            'hasCheckedIn',
            'hasCheckedOut',
            'thisMonthAttendances'
        ));
    }

    /**
     * Admin attendance dashboard
     */
    private function adminIndex(): View
    {
        $todayAttendances = Attendance::with(['user', 'location'])
            ->today()
            ->orderBy('attendance_time', 'desc')
            ->get();

        $stats = [
            'total_today' => $todayAttendances->count(),
            'check_in_today' => $todayAttendances->where('type', 'check_in')->count(),
            'check_out_today' => $todayAttendances->where('type', 'check_out')->count(),
            'verified_today' => $todayAttendances->where('is_verified', true)->count(),
        ];

        return view('admin.attendance.index', compact('todayAttendances', 'stats'));
    }

    /**
     * Show check-in form
     */
    public function checkIn(): View
    {
        $user = auth()->user();

        if ($user->hasCheckedInToday()) {
            return redirect()->route('attendance.index')
                ->withErrors(['error' => 'You have already checked in today']);
        }

        if (! $user->is_face_enrolled) {
            return redirect()->route('face.enroll')
                ->withErrors(['error' => 'Please enroll your face first before attendance']);
        }

        $locations = Location::active()->get();

        return view('attendance.check-in', compact('locations'));
    }

    /**
     * Show check-out form
     */
    public function checkOut(): View
    {
        $user = auth()->user();

        if (! $user->hasCheckedInToday()) {
            return redirect()->route('attendance.index')
                ->withErrors(['error' => 'You need to check in first']);
        }

        if ($user->hasCheckedOutToday()) {
            return redirect()->route('attendance.index')
                ->withErrors(['error' => 'You have already checked out today']);
        }

        $checkInRecord = $user->getTodayCheckIn();

        return view('attendance.check-out', compact('checkInRecord'));
    }

    /**
     * Process attendance with face recognition
     */
    public function processAttendance(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            $validated = $request->validate([
                'type' => 'required|in:check_in,check_out',
                'location_id' => 'required_if:type,check_in|exists:locations,id',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'face_image' => 'required|string', // base64 image
                'notes' => 'nullable|string|max:500',
            ]);

            // Validate attendance rules
            if ($validated['type'] === 'check_in' && $user->hasCheckedInToday()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already checked in today',
                ], 422);
            }

            if ($validated['type'] === 'check_out' && ! $user->hasCheckedInToday()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You need to check in first',
                ], 422);
            }

            if ($validated['type'] === 'check_out' && $user->hasCheckedOutToday()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already checked out today',
                ], 422);
            }

            // For check-in, validate location if provided
            $location = null;
            if ($validated['type'] === 'check_in' && isset($validated['location_id'])) {
                $location = Location::findOrFail($validated['location_id']);

                // Check if user is within location radius
                if (isset($validated['latitude']) && isset($validated['longitude'])) {
                    if (! $location->isWithinRadius($validated['latitude'], $validated['longitude'])) {
                        return response()->json([
                            'success' => false,
                            'message' => 'You are not within the allowed location radius',
                        ], 422);
                    }
                }
            }

            // For check-out, use the same location as check-in
            if ($validated['type'] === 'check_out') {
                $checkInRecord = $user->getTodayCheckIn();
                $location = $checkInRecord->location;
            }

            // Process face image
            $base64Image = $this->faceService->processBase64Image($validated['face_image']);

            // Verify face against enrolled user
            $faceVerification = $this->faceService->verifyFace(
                $user->employee_id ?: $user->id,
                $base64Image
            );

            if ($faceVerification['status'] !== '200') {
                return response()->json([
                    'success' => false,
                    'message' => 'Face verification failed: '.($faceVerification['status_message'] ?? 'Unknown error'),
                ], 422);
            }

            $isVerified = $faceVerification['verified'] ?? false;
            $confidenceLevel = $faceVerification['similarity'] ?? 0;

            // Create attendance record
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'location_id' => $location->id,
                'type' => $validated['type'],
                'attendance_time' => now(),
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'face_image' => $base64Image,
                'confidence_level' => $confidenceLevel,
                'is_verified' => $isVerified,
                'notes' => $validated['notes'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => ucfirst($validated['type']).' successful',
                'data' => [
                    'attendance_id' => $attendance->id,
                    'verified' => $isVerified,
                    'confidence_level' => $confidenceLevel,
                    'time' => $attendance->formatted_attendance_time,
                    'location' => $location->name,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance processing failed: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show attendance history
     */
    public function history(Request $request): View
    {
        $user = auth()->user();

        $query = $user->attendances()->with('location');

        // Filter by date range if provided
        if ($request->filled('start_date')) {
            $query->whereDate('attendance_time', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('attendance_time', '<=', $request->end_date);
        }

        // Filter by type if provided
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $attendances = $query->orderBy('attendance_time', 'desc')->paginate(20);

        return view('attendance.history', compact('attendances'));
    }

    /**
     * Admin view all attendances
     */
    public function adminHistory(Request $request)
    {
        // Check if this is an export request
        if ($request->has('export')) {
            return $this->exportAttendanceData($request);
        }

        // Admin middleware is already applied in routes/web.php
        // No need for manual admin check here

        $query = Attendance::with(['user', 'location']);

        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by location if provided
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filter by date range if provided
        if ($request->filled('start_date')) {
            $query->whereDate('attendance_time', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('attendance_time', '<=', $request->end_date);
        }

        // Filter by type if provided
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $attendances = $query->orderBy('attendance_time', 'desc')->paginate(20);
        $users = User::where('role', 'user')->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('admin.attendance.history', compact('attendances', 'users', 'locations'));
    }

    /**
     * Export attendance data
     */
    private function exportAttendanceData(Request $request): Response
    {
        $query = Attendance::with(['user', 'location']);

        // Apply the same filters as the view
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('attendance_time', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('attendance_time', '<=', $request->end_date);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $attendances = $query->orderBy('attendance_time', 'desc')->get();

        $format = $request->get('export', 'csv');
        $filename = 'attendance_report_'.now()->format('Y-m-d_H-i-s');

        if ($format === 'csv') {
            return $this->exportAsCsv($attendances, $filename);
        } elseif ($format === 'excel') {
            return $this->exportAsExcel($attendances, $filename);
        }

        abort(400, 'Invalid export format');
    }

    /**
     * Export data as CSV
     */
    private function exportAsCsv($attendances, $filename): Response
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Employee ID',
                'Employee Name',
                'Type',
                'Date',
                'Time',
                'Location',
                'Address',
                'Verified',
                'Confidence Level',
                'Notes',
                'Created At',
            ]);

            // CSV Data
            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->id,
                    $attendance->user->employee_id ?: $attendance->user->id,
                    $attendance->user->name,
                    ucfirst(str_replace('_', ' ', $attendance->type)),
                    $attendance->attendance_time->format('Y-m-d'),
                    $attendance->attendance_time->format('H:i:s'),
                    $attendance->location->name ?? 'N/A',
                    $attendance->location->address ?? 'N/A',
                    $attendance->is_verified ? 'Yes' : 'No',
                    $attendance->confidence_level ? number_format($attendance->confidence_level * 100, 2).'%' : 'N/A',
                    $attendance->notes ?: '',
                    $attendance->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export data as Excel (using CSV format for simplicity)
     */
    private function exportAsExcel($attendances, $filename): Response
    {
        // For simplicity, we'll use CSV format with .xlsx extension
        // In a real application, you might want to use a library like PhpSpreadsheet
        return $this->exportAsCsv($attendances, $filename.'.xlsx');
    }

    /**
     * Show specific attendance details
     */
    public function show(Attendance $attendance): View
    {
        $user = auth()->user();

        // Regular users can only view their own attendance
        if (! $user->isAdmin() && $attendance->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $attendance->load(['user', 'location']);

        return view('attendance.show', compact('attendance'));
    }
}
