<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'name', 'course_id', 'max_students', 'current_students',
        'status', 'description'
    ];

    protected $casts = [
        'max_students' => 'integer',
        'current_students' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // БЕЗ ->using() - работает без ошибок
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'group_student')
                    ->withPivot('enrolled_at', 'left_at', 'status')
                    ->withTimestamps();
    }

    public function activeStudents()
    {
        return $this->students()->wherePivot('status', 'active');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'forming' => 'Формируется',
            'active' => 'Идёт набор',
            'completed' => 'Завершена',
            'cancelled' => 'Отменена',
            default => 'Неизвестно',
        };
    }

    public function getIsFullAttribute(): bool
    {
        return $this->current_students >= $this->max_students;
    }

    public function getAvailablePlacesAttribute(): int
    {
        return max(0, $this->max_students - $this->current_students);
    }
}
