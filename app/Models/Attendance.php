<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'schedule_id',
        'student_id',
        'status',
        'reason',
        'marked_by',
        'marked_at'
    ];

    protected $casts = [
        'marked_at' => 'datetime',
        'status' => 'string',
    ];

    // Связи
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function subscriptionExtension()
    {
        return $this->hasOne(SubscriptionExtension::class);
    }

    // Аттрибуты
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'present' => 'Присутствовал',
            'late' => 'Опоздал',
            'absent_justified' => 'Отсутствовал по уважительной причине',
            'absent_unjustified' => 'Отсутствовал без уважительной причины',
            default => 'Неизвестно'
        };
    }
}
