<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'student_name',
        'student_age',
        'parent_phone',
        'parent_email',
        'course_id',
        'status',
        'comment'
    ];

    protected $casts = [
        'student_age' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'new' => 'Новая',
            'processed' => 'Обработана',
            'approved' => 'Одобрена',
            'rejected' => 'Отклонена',
            default => 'Новая'
        };
    }
}
