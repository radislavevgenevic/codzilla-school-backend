<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progress extends Model
{
    protected $fillable = [
        'student_id', 'course_id', 'completed_lessons_count',
        'current_lesson_id', 'percent', 'last_attendance_at'
    ];

    protected $casts = [
        'percent' => 'decimal:2',
        'last_attendance_at' => 'datetime',
        'completed_lessons_count' => 'integer',
    ];

    // Связи
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function currentLesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'current_lesson_id');
    }

    /**
     * Пересчитать прогресс ученика по курсу
     */
    public static function recalculate($studentId, $courseId)
    {
        // Получаем общее количество уроков в курсе
        $totalLessons = Lesson::where('course_id', $courseId)->count();

        if ($totalLessons === 0) {
            return self::updateOrCreate(
                ['student_id' => $studentId, 'course_id' => $courseId],
                [
                    'completed_lessons_count' => 0,
                    'percent' => 0,
                    'last_attendance_at' => now(),
                ]
            );
        }

        // Считаем количество посещённых уроков
        $completedLessons = Attendance::where('student_id', $studentId)
            ->whereHas('schedule.lesson', function($q) use ($courseId) {
                $q->where('course_id', $courseId);
            })
            ->where('status', 'present')
            ->count();

        // Вычисляем процент
        $percent = ($completedLessons / $totalLessons) * 100;

        // Находим текущий урок
        $currentLesson = Lesson::where('course_id', $courseId)
            ->where('order', '<=', $completedLessons + 1)
            ->orderBy('order', 'desc')
            ->first();

        return self::updateOrCreate(
            ['student_id' => $studentId, 'course_id' => $courseId],
            [
                'completed_lessons_count' => $completedLessons,
                'current_lesson_id' => $currentLesson?->id,
                'percent' => $percent,
                'last_attendance_at' => now(),
            ]
        );
    }
}
