<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'location_id',
        'date',
        'check_in',
        'check_out',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'check_in_photo',
        'check_out_photo',
        'face_similarity_in',
        'face_similarity_out',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i:s',
        'check_out' => 'datetime:H:i:s',
        'check_in_latitude' => 'decimal:8',
        'check_in_longitude' => 'decimal:8',
        'check_out_latitude' => 'decimal:8',
        'check_out_longitude' => 'decimal:8',
        'face_similarity_in' => 'decimal:2',
        'face_similarity_out' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function getWorkingHoursAttribute(): ?float
    {
        if (!$this->check_in || !$this->check_out) {
            return null;
        }

        $checkIn = \Carbon\Carbon::parse($this->check_in);
        $checkOut = \Carbon\Carbon::parse($this->check_out);

        return $checkOut->diffInHours($checkIn, true);
    }

    public function isLate(): bool
    {
        if (!$this->check_in || !$this->location) {
            return false;
        }

        $checkInTime = \Carbon\Carbon::parse($this->check_in);
        $workStartTime = \Carbon\Carbon::parse($this->location->work_start_time);

        return $checkInTime->gt($workStartTime);
    }

    public function isEarlyCheckout(): bool
    {
        if (!$this->check_out || !$this->location) {
            return false;
        }

        $checkOutTime = \Carbon\Carbon::parse($this->check_out);
        $workEndTime = \Carbon\Carbon::parse($this->location->work_end_time);

        return $checkOutTime->lt($workEndTime);
    }

    public function updateStatus(): void
    {
        if ($this->isLate()) {
            $this->status = 'late';
        } elseif ($this->isEarlyCheckout() && $this->getWorkingHoursAttribute() < 4) {
            $this->status = 'half_day';
        } else {
            $this->status = 'present';
        }

        $this->save();
    }
}