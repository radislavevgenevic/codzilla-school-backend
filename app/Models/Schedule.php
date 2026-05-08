<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'lesson_id',
        'group_id',        // НОВОЕ: привязка к группе
        'start_time',
        'end_time',
        'room'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // ========== СВЯЗИ ==========

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    // НОВАЯ СВЯЗЬ: расписание принадлежит группе
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // ========== ПОЛУЧЕНИЕ УЧЕНИКОВ ==========

    // Старый метод (оставляем для совместимости, но лучше не использовать)
    public function getAvailableStudentsFromCourse()
    {
        // DEPRECATED: больше не используем, так как ученики теперь в группах
        return $this->lesson->course->students()
            ->wherePivot('status', 'enrolled')
            ->get();
    }

    // НОВЫЙ МЕТОД: получаем учеников из группы
    public function getAvailableStudents()
    {
        if (!$this->group_id) {
            return collect(); // Если нет группы, возвращаем пустую коллекцию
        }

        return $this->group->activeStudents()->get();
    }

    // Альтернативный метод с проверками
    public function getStudentsForAttendance()
    {
        return $this->getAvailableStudents();
    }

    // ========== СТАТИСТИКА ==========

    // Старый метод (работает, но не учитывает группы)
    public function getOldStatsAttribute()
    {
        $total = $this->getAvailableStudentsFromCourse()->count();
        $present = $this->attendances()->where('status', 'present')->count();
        $absent = $this->attendances()->where('status', 'absent')->count();
        $late = $this->attendances()->where('status', 'late')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'percent' => $total > 0 ? round(($present / $total) * 100) : 0
        ];
    }

    // НОВЫЙ МЕТОД: статистика по группе
    public function getStatsAttribute()
    {
        $students = $this->getAvailableStudents();
        $total = $students->count();

        // Получаем отметки только для учеников этой группы
        $present = $this->attendances()
            ->whereIn('student_id', $students->pluck('id'))
            ->where('status', 'present')
            ->count();

        $absent = $this->attendances()
            ->whereIn('student_id', $students->pluck('id'))
            ->where('status', 'absent')
            ->count();

        $late = $this->attendances()
            ->whereIn('student_id', $students->pluck('id'))
            ->where('status', 'late')
            ->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'not_marked' => $total - ($present + $absent + $late), // НЕ ОТМЕЧЕННЫЕ
            'percent' => $total > 0 ? round(($present / $total) * 100) : 0
        ];
    }

    // ========== ВСПОМОГАТЕЛЬНЫЕ МЕТОДЫ ==========

    // Проверить, прошло ли занятие
    public function getIsPastAttribute(): bool
    {
        return $this->start_time->isPast();
    }

    // Проверить, сегодня ли занятие
    public function getIsTodayAttribute(): bool
    {
        return $this->start_time->isToday();
    }

    // Проверить, можно ли ещё отмечать посещаемость
    public function getCanMarkAttendanceAttribute(): bool
    {
        // Можно отмечать за 1 час до начала и до 7 дней после
        return now()->gte($this->start_time->copy()->subHour())
            && now()->lte($this->start_time->copy()->addDays(7));
    }

    // Форматированное время
    public function getFormattedTimeAttribute(): string
    {
        return $this->start_time->format('d.m.Y H:i') . ' - ' . $this->end_time->format('H:i');
    }

    // Название занятия для вывода
    public function getTitleAttribute(): string
    {
        $lessonTitle = $this->lesson?->title ?? 'Занятие';
        $groupName = $this->group?->name ?? 'Без группы';
        return "{$lessonTitle} ({$groupName})";
    }
}
