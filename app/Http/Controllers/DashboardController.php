<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Location;
use App\Models\User;
use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private FaceRecognitionService $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    /**
     * Display dashboard with attendance functionality
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard($request);
        }

        return $this->userDashboard($request);
    }

    /**
     * Admin dashboard with attendance management
     */
    private function adminDashboard(Request $request): View
    {
        $todayAttendances = Attendance::with(['user', 'location'])
            ->today()
            ->orderBy('attendance_time', 'desc')
            ->get();

        $totalUsers = User::where('role', 'user')->count();
        $totalLocations = Location::count();
        $enrolledUsers = User::where('is_face_enrolled', true)->count();

        $stats = [
            'total_today' => $todayAttendances->count(),
            'check_in_today' => $todayAttendances->where('type', 'check_in')->count(),
            'check_out_today' => $todayAttendances->where('type', 'check_out')->count(),
            'verified_today' => $todayAttendances->where('is_verified', true)->count(),
        ];

        return view('dashboard.admin', compact(
            'todayAttendances',
            'totalUsers',
            'totalLocations',
            'enrolledUsers',
            'stats'
        ));
    }

    /**
     * User dashboard with attendance functionality
     */
    private function userDashboard(Request $request): View
    {
        $user = auth()->user();
        $locations = $user->assignedLocation && $user->assignedLocation->is_active
            ? collect([$user->assignedLocation])
            : collect();
        $todayAttendances = $user->getTodayAttendances();
        $hasCheckedIn = $user->hasCheckedInToday();
        $hasCheckedOut = $user->hasCheckedOutToday();

        // Get this month's attendance count
        $thisMonthAttendances = $user->attendances()
            ->thisMonth()
            ->count();

        // Attendance history for user
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

        return view('dashboard.user', compact(
            'user',
            'locations',
            'todayAttendances',
            'hasCheckedIn',
            'hasCheckedOut',
            'thisMonthAttendances',
            'attendances'
        ));
    }
}
