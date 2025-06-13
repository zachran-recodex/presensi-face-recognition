<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Location;
use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected FaceRecognitionService $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    public function index(Request $request)
    {
        $query = Attendance::with(['employee', 'location']);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);

        $employees = Employee::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('attendances.index', compact('attendances', 'employees', 'locations'));
    }

    public function employee()
    {
        $employee = Employee::where('employee_id', Auth::user()->username)
            ->orWhere('email', Auth::user()->email)
            ->first();

        if (!$employee) {
            return view('attendances.employee-not-found');
        }

        $todayAttendance = $employee->todayAttendance();
        $recentAttendances = $employee->attendances()
            ->with('location')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return view('attendances.employee', compact('employee', 'todayAttendance', 'recentAttendances'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'face_image' => 'required|string', // base64 image
        ]);

        $employee = Employee::where('employee_id', Auth::user()->username)
            ->orWhere('email', Auth::user()->email)
            ->first();

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }

        if (!$employee->face_registered) {
            return response()->json(['success' => false, 'message' => 'Face not registered'], 400);
        }

        $location = Location::findOrFail($request->location_id);

        // Check if employee is assigned to this location
        if (!$employee->locations()->where('location_id', $location->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Not assigned to this location'], 403);
        }

        // Check location radius
        if (!$location->isWithinRadius($request->latitude, $request->longitude)) {
            return response()->json(['success' => false, 'message' => 'Outside location radius'], 400);
        }

        // Check if already checked in today
        $todayAttendance = $employee->attendances()->whereDate('date', today())->first();
        if ($todayAttendance && $todayAttendance->check_in) {
            return response()->json(['success' => false, 'message' => 'Already checked in today'], 400);
        }

        try {
            // Verify face
            $faceResult = $this->faceService->verifyFace($employee->employee_id, $request->face_image);

            if (!$this->faceService->isVerificationSuccessful($faceResult)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Face verification failed',
                    'similarity' => $faceResult['similarity'] ?? 0
                ], 400);
            }

            // Create or update attendance
            $attendance = $employee->attendances()->updateOrCreate(
                ['date' => today()],
                [
                    'location_id' => $location->id,
                    'check_in' => now(),
                    'check_in_latitude' => $request->latitude,
                    'check_in_longitude' => $request->longitude,
                    'check_in_photo' => $request->face_image,
                    'face_similarity_in' => $faceResult['similarity'],
                ]
            );

            $attendance->updateStatus();

            return response()->json([
                'success' => true,
                'message' => 'Check-in successful',
                'attendance' => $attendance->load('location'),
                'face_similarity' => $faceResult['similarity']
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Face verification error: ' . $e->getMessage()], 500);
        }
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'face_image' => 'required|string', // base64 image
        ]);

        $employee = Employee::where('employee_id', Auth::user()->username)
            ->orWhere('email', Auth::user()->email)
            ->first();

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }

        $todayAttendance = $employee->attendances()->whereDate('date', today())->first();

        if (!$todayAttendance || !$todayAttendance->check_in) {
            return response()->json(['success' => false, 'message' => 'Must check-in first'], 400);
        }

        if ($todayAttendance->check_out) {
            return response()->json(['success' => false, 'message' => 'Already checked out today'], 400);
        }

        $location = $todayAttendance->location;

        // Check location radius
        if (!$location->isWithinRadius($request->latitude, $request->longitude)) {
            return response()->json(['success' => false, 'message' => 'Outside location radius'], 400);
        }

        try {
            // Verify face
            $faceResult = $this->faceService->verifyFace($employee->employee_id, $request->face_image);

            if (!$this->faceService->isVerificationSuccessful($faceResult)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Face verification failed',
                    'similarity' => $faceResult['similarity'] ?? 0
                ], 400);
            }

            // Update attendance
            $todayAttendance->update([
                'check_out' => now(),
                'check_out_latitude' => $request->latitude,
                'check_out_longitude' => $request->longitude,
                'check_out_photo' => $request->face_image,
                'face_similarity_out' => $faceResult['similarity'],
            ]);

            $todayAttendance->updateStatus();

            return response()->json([
                'success' => true,
                'message' => 'Check-out successful',
                'attendance' => $todayAttendance->refresh()->load('location'),
                'face_similarity' => $faceResult['similarity'],
                'working_hours' => $todayAttendance->working_hours
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Face verification error: ' . $e->getMessage()], 500);
        }
    }

    public function report(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $employees = Employee::with(['attendances' => function ($query) use ($year, $month) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month)
                  ->with('location');
        }, 'locations'])->where('is_active', true)->get();

        $workingDays = $this->getWorkingDaysInMonth($year, $month);

        return view('attendances.report', compact('employees', 'month', 'year', 'workingDays'));
    }

    public function exportReport(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $employees = Employee::with(['attendances' => function ($query) use ($year, $month) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month)
                  ->with('location');
        }])->where('is_active', true)->get();

        $filename = "attendance_report_{$year}_{$month}.csv";

        return response()->streamDownload(function () use ($employees, $year, $month) {
            $handle = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($handle, [
                'Employee ID', 'Name', 'Department', 'Date', 'Check In', 'Check Out',
                'Location', 'Status', 'Working Hours', 'Face Similarity In', 'Face Similarity Out'
            ]);

            foreach ($employees as $employee) {
                foreach ($employee->attendances as $attendance) {
                    fputcsv($handle, [
                        $employee->employee_id,
                        $employee->name,
                        $employee->department,
                        $attendance->date->format('Y-m-d'),
                        $attendance->check_in ? $attendance->check_in->format('H:i:s') : '',
                        $attendance->check_out ? $attendance->check_out->format('H:i:s') : '',
                        $attendance->location->name,
                        ucfirst($attendance->status),
                        $attendance->working_hours ?? 0,
                        $attendance->face_similarity_in ?? '',
                        $attendance->face_similarity_out ?? '',
                    ]);
                }
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function getWorkingDaysInMonth($year, $month): int
    {
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $workingDays = 0;

        while ($startDate->lte($endDate)) {
            if ($startDate->isWeekday()) {
                $workingDays++;
            }
            $startDate->addDay();
        }

        return $workingDays;
    }
}
