<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    protected $fillable = [
        'student_id',
        'name',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function extensions(): HasMany
    {
        return $this->hasMany(SubscriptionExtension::class);
    }

    public function getRemainingDaysAttribute(): int
    {
        if (!$this->end_date || $this->status === 'cancelled') {
            return 0;
        }

        if ($this->end_date->copy()->endOfDay()->isPast()) {
            return 0;
        }

        return now()->startOfDay()->diffInDays($this->end_date->copy()->startOfDay());
    }

    public function getEffectiveStatusAttribute(): string
    {
        if ($this->status === 'active' && $this->end_date?->copy()->endOfDay()->isPast()) {
            return 'expired';
        }

        return $this->status;
    }

    public function extend(
        int $days,
        ?User $createdBy = null,
        ?string $reason = null,
        ?Attendance $attendance = null
    ): SubscriptionExtension
    {
        $previousEndDate = $this->end_date->copy();
        $newEndDate = $previousEndDate->copy()->addDays($days);

        $this->forceFill([
            'end_date' => $newEndDate,
            'status' => $this->status === 'expired' ? 'active' : $this->status,
        ])->save();

        return $this->extensions()->create([
            'created_by' => $createdBy?->id,
            'attendance_id' => $attendance?->id,
            'days' => $days,
            'previous_end_date' => $previousEndDate,
            'new_end_date' => $newEndDate,
            'reason' => $reason,
        ]);
    }
}
