<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display attendance reports dashboard
     */
    public function index(Request $request): View
    {
        // Date range filters
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Build base query
        $query = Attendance::with(['user', 'location'])
            ->whereBetween('attendance_time', [$startDate->startOfDay(), $endDate->endOfDay()]);

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $isVerified = $request->status === 'verified';
            $query->where('is_verified', $isVerified);
        }

        $attendances = $query->orderBy('attendance_time', 'desc')->paginate(50)->withQueryString();

        // Statistics
        $stats = $this->getReportStatistics($startDate, $endDate, $request);

        // Get filter options
        $users = User::orderBy('name')->get(['id', 'name', 'employee_id']);
        $locations = Location::orderBy('name')->get(['id', 'name']);

        return view('admin.reports.index', compact(
            'attendances', 
            'stats', 
            'users', 
            'locations',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export attendance report
     */
    public function export(Request $request)
    {
        // Date range filters
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Build query
        $query = Attendance::with(['user', 'location'])
            ->whereBetween('attendance_time', [$startDate->startOfDay(), $endDate->endOfDay()]);

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $isVerified = $request->status === 'verified';
            $query->where('is_verified', $isVerified);
        }

        $attendances = $query->orderBy('attendance_time', 'desc')->get();

        // Export format
        $format = $request->get('format', 'csv');

        if ($format === 'csv') {
            return $this->exportToCsv($attendances, $startDate, $endDate);
        }

        // Default to CSV
        return $this->exportToCsv($attendances, $startDate, $endDate);
    }

    /**
     * Daily summary report
     */
    public function dailySummary(Request $request): View
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : Carbon::today();
        
        // Get daily statistics by user
        $userStats = User::with(['attendances' => function($query) use ($date) {
            $query->whereDate('attendance_time', $date)
                  ->orderBy('attendance_time');
        }])->get()->map(function($user) {
            $attendances = $user->attendances;
            $checkIn = $attendances->where('type', 'check_in')->first();
            $checkOut = $attendances->where('type', 'check_out')->last();
            
            return [
                'user' => $user,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_attendances' => $attendances->count(),
                'hours_worked' => $checkIn && $checkOut ? 
                    $checkOut->attendance_time->diffInHours($checkIn->attendance_time) : null
            ];
        })->filter(function($stat) {
            return $stat['total_attendances'] > 0;
        });

        // Overall statistics for the day
        $dailyStats = [
            'total_attendances' => Attendance::whereDate('attendance_time', $date)->count(),
            'unique_users' => Attendance::whereDate('attendance_time', $date)->distinct('user_id')->count(),
            'check_ins' => Attendance::whereDate('attendance_time', $date)->where('type', 'check_in')->count(),
            'check_outs' => Attendance::whereDate('attendance_time', $date)->where('type', 'check_out')->count(),
            'verified_attendances' => Attendance::whereDate('attendance_time', $date)->where('is_verified', true)->count(),
        ];

        return view('admin.reports.daily-summary', compact('userStats', 'dailyStats', 'date'));
    }

    /**
     * Monthly summary report
     */
    public function monthlySummary(Request $request): View
    {
        $month = $request->filled('month') ? Carbon::parse($request->month) : Carbon::now();
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        // Get monthly statistics by user
        $userStats = User::with(['attendances' => function($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('attendance_time', [$startOfMonth, $endOfMonth]);
        }])->get()->map(function($user) use ($startOfMonth, $endOfMonth) {
            $attendances = $user->attendances;
            
            return [
                'user' => $user,
                'total_attendances' => $attendances->count(),
                'check_ins' => $attendances->where('type', 'check_in')->count(),
                'check_outs' => $attendances->where('type', 'check_out')->count(),
                'verified_attendances' => $attendances->where('is_verified', true)->count(),
                'days_present' => $attendances->groupBy(function($attendance) {
                    return $attendance->attendance_time->format('Y-m-d');
                })->count(),
            ];
        })->filter(function($stat) {
            return $stat['total_attendances'] > 0;
        });

        // Overall monthly statistics
        $monthlyStats = [
            'total_attendances' => Attendance::whereBetween('attendance_time', [$startOfMonth, $endOfMonth])->count(),
            'unique_users' => Attendance::whereBetween('attendance_time', [$startOfMonth, $endOfMonth])->distinct('user_id')->count(),
            'working_days' => $startOfMonth->diffInWeekdays($endOfMonth),
            'average_daily_attendances' => 0,
        ];

        if ($monthlyStats['working_days'] > 0) {
            $monthlyStats['average_daily_attendances'] = round($monthlyStats['total_attendances'] / $monthlyStats['working_days'], 1);
        }

        return view('admin.reports.monthly-summary', compact('userStats', 'monthlyStats', 'month'));
    }

    /**
     * Location usage report
     */
    public function locationUsage(Request $request): View
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $locationStats = Location::with(['attendances' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('attendance_time', [$startDate, $endDate]);
        }])->get()->map(function($location) {
            $attendances = $location->attendances;
            
            return [
                'location' => $location,
                'total_attendances' => $attendances->count(),
                'unique_users' => $attendances->pluck('user_id')->unique()->count(),
                'check_ins' => $attendances->where('type', 'check_in')->count(),
                'check_outs' => $attendances->where('type', 'check_out')->count(),
                'verified_attendances' => $attendances->where('is_verified', true)->count(),
            ];
        })->sortByDesc('total_attendances');

        return view('admin.reports.location-usage', compact('locationStats', 'startDate', 'endDate'));
    }

    /**
     * Get report statistics
     */
    private function getReportStatistics(Carbon $startDate, Carbon $endDate, Request $request): array
    {
        $baseQuery = Attendance::whereBetween('attendance_time', [$startDate->startOfDay(), $endDate->endOfDay()]);

        // Apply same filters as main query
        if ($request->filled('user_id')) {
            $baseQuery->where('user_id', $request->user_id);
        }

        if ($request->filled('location_id')) {
            $baseQuery->where('location_id', $request->location_id);
        }

        if ($request->filled('type')) {
            $baseQuery->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $isVerified = $request->status === 'verified';
            $baseQuery->where('is_verified', $isVerified);
        }

        return [
            'total_attendances' => $baseQuery->count(),
            'unique_users' => $baseQuery->distinct('user_id')->count(),
            'check_ins' => (clone $baseQuery)->where('type', 'check_in')->count(),
            'check_outs' => (clone $baseQuery)->where('type', 'check_out')->count(),
            'verified_attendances' => (clone $baseQuery)->where('is_verified', true)->count(),
            'unique_locations' => (clone $baseQuery)->distinct('location_id')->count(),
        ];
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($attendances, Carbon $startDate, Carbon $endDate)
    {
        $filename = 'attendance_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Date',
                'Time',
                'Employee Name',
                'Employee ID',
                'Email',
                'Type',
                'Location',
                'Location Address',
                'Status',
                'Confidence Level',
                'Latitude',
                'Longitude',
                'Notes'
            ]);

            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->attendance_time->format('Y-m-d'),
                    $attendance->attendance_time->format('H:i:s'),
                    $attendance->user->name,
                    $attendance->user->employee_id ?? $attendance->user->id,
                    $attendance->user->email,
                    ucfirst(str_replace('_', ' ', $attendance->type)),
                    $attendance->location->name,
                    $attendance->location->address,
                    $attendance->is_verified ? 'Verified' : 'Pending',
                    $attendance->confidence_level ? number_format($attendance->confidence_level * 100, 1) . '%' : '',
                    $attendance->latitude,
                    $attendance->longitude,
                    $attendance->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}