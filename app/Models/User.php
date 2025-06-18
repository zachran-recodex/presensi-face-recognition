<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'employee_id',
        'phone',
        'face_image',
        'is_face_enrolled',
        'location_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'face_image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_face_enrolled' => 'boolean',
        ];
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function assignedLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is admin (includes super admin)
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Check if user is regular admin (not super admin)
     */
    public function isRegularAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get today's attendances
     */
    public function getTodayAttendances()
    {
        return $this->attendances()
            ->whereDate('attendance_time', Carbon::today())
            ->orderBy('attendance_time')
            ->get();
    }

    /**
     * Get latest check in today
     */
    public function getTodayCheckIn(): ?Attendance
    {
        return $this->attendances()
            ->where('type', 'check_in')
            ->whereDate('attendance_time', Carbon::today())
            ->latest('attendance_time')
            ->first();
    }

    /**
     * Get latest check out today
     */
    public function getTodayCheckOut(): ?Attendance
    {
        return $this->attendances()
            ->where('type', 'check_out')
            ->whereDate('attendance_time', Carbon::today())
            ->latest('attendance_time')
            ->first();
    }

    /**
     * Check if user has checked in today
     */
    public function hasCheckedInToday(): bool
    {
        return $this->getTodayCheckIn() !== null;
    }

    /**
     * Check if user has checked out today
     */
    public function hasCheckedOutToday(): bool
    {
        return $this->getTodayCheckOut() !== null;
    }
}
