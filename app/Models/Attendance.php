<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_id',
        'type',
        'attendance_time',
        'latitude',
        'longitude',
        'face_image',
        'confidence_level',
        'is_verified',
        'is_late',
        'late_minutes',
        'notes',
    ];

    protected $casts = [
        'attendance_time' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'confidence_level' => 'decimal:2',
        'is_verified' => 'boolean',
        'is_late' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('attendance_time', Carbon::today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('attendance_time', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('attendance_time', Carbon::now()->month)
            ->whereYear('attendance_time', Carbon::now()->year);
    }

    public function scopeCheckIn($query)
    {
        return $query->where('type', 'check_in');
    }

    public function scopeCheckOut($query)
    {
        return $query->where('type', 'check_out');
    }

    public function getFormattedAttendanceTimeAttribute(): string
    {
        return $this->attendance_time->format('d/m/Y H:i:s');
    }

    /**
     * Check if user already checked in today
     */
    public static function hasCheckedInToday(int $userId): bool
    {
        return self::where('user_id', $userId)
            ->where('type', 'check_in')
            ->whereDate('attendance_time', Carbon::today())
            ->exists();
    }

    /**
     * Check if user already checked out today
     */
    public static function hasCheckedOutToday(int $userId): bool
    {
        return self::where('user_id', $userId)
            ->where('type', 'check_out')
            ->whereDate('attendance_time', Carbon::today())
            ->exists();
    }

    /**
     * Get today's check in record for user
     */
    public static function getTodayCheckIn(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('type', 'check_in')
            ->whereDate('attendance_time', Carbon::today())
            ->first();
    }
}
