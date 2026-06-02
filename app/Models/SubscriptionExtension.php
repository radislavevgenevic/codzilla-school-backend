<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionExtension extends Model
{
    protected $fillable = [
        'subscription_id',
        'created_by',
        'attendance_id',
        'days',
        'previous_end_date',
        'new_end_date',
        'reason',
    ];

    protected $casts = [
        'previous_end_date' => 'date',
        'new_end_date' => 'date',
        'days' => 'integer',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }
}
