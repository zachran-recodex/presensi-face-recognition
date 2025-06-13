<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'phone',
        'department',
        'position',
        'face_registered',
        'face_gallery_id',
        'is_active',
    ];

    protected $casts = [
        'face_registered' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'employee_locations')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function primaryLocation(): BelongsToMany
    {
        return $this->locations()->wherePivot('is_primary', true);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function todayAttendance()
    {
        return $this->attendances()->whereDate('date', today())->first();
    }

    public function monthlyAttendances($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        return $this->attendances()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date');
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn($name) => substr($name, 0, 1))
            ->implode('');
    }
}
