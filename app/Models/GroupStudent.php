<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupStudent extends Model
{
    /**
     * Таблица с дополнительными полями
     */
    protected $table = 'group_student';

    protected $fillable = [
        'group_id',
        'student_id',
        'enrolled_at',
        'left_at',
        'status'
    ];

    protected $casts = [
        'enrolled_at' => 'date',
        'left_at' => 'date',
    ];

    /**
     * Связи
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Аттрибуты
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'active' => 'Активен',
            'graduated' => 'Выпущен',
            'dropped' => 'Отчислен',
            default => 'Неизвестно',
        };
    }

    /**
     * Scope для активных учеников
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
