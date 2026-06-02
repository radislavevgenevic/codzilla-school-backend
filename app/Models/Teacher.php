<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hourly_rate',
        'bio',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
    ];

    // Связи
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    /**
     * Получить всех учеников преподавателя через группы
     */
    public function students()
    {
        return Student::whereIn(
            'id',
            Group::whereHas('courses', function ($query) {
                $query->where('teacher_id', $this->id);
            })->pluck('id')->toArray() ?? []
        );
    }

    /**
     * Рассчитать зарплату за период
     */
    public function calculateSalary($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = now()->startOfDay();
        }
        if (!$endDate) {
            $endDate = now()->endOfDay();
        }

        // Получаем все проведенные занятия преподавателя в этот период
        $schedules = Schedule::whereHas('group.course', function ($query) {
            $query->where('teacher_id', $this->id);
        })
        ->whereBetween('start_time', [$startDate, $endDate])
        ->where('end_time', '<=', now())
        ->get();

        $totalSalary = 0;
        $totalMinutes = 0;

        foreach ($schedules as $schedule) {
            $minutes = $schedule->getDurationInMinutes();
            $salary = ($this->hourly_rate / 60) * $minutes;
            $totalSalary += $salary;
            $totalMinutes += $minutes;
        }

        $totalHours = round($totalMinutes / 60, 2);

        return [
            'total' => round($totalSalary, 2),
            'minutes' => $totalMinutes,
            'hours' => $totalHours,
            'rate' => $this->hourly_rate,
            'lessons_count' => $schedules->count(),
        ];
    }

    /**
     * Получить детальную информацию о заработках (по каждому занятию)
     */
    public function getSalaryDetails($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = now()->startOfDay();
        }
        if (!$endDate) {
            $endDate = now()->endOfDay();
        }

        $schedules = Schedule::whereHas('group.course', function ($query) {
            $query->where('teacher_id', $this->id);
        })
        ->with(['group', 'lesson'])
        ->whereBetween('start_time', [$startDate, $endDate])
        ->where('end_time', '<=', now())
        ->orderBy('start_time')
        ->get()
        ->map(function ($schedule) {
            $minutes = $schedule->getDurationInMinutes();
            $salary = ($this->hourly_rate / 60) * $minutes;

            return [
                'id' => $schedule->id,
                'date' => $schedule->start_time->toDateString(),
                'time' => $schedule->start_time->format('H:i') . ' - ' . $schedule->end_time->format('H:i'),
                'lesson' => $schedule->lesson?->title ?? 'N/A',
                'group' => $schedule->group?->name ?? 'N/A',
                'duration_minutes' => $minutes,
                'duration_hours' => round($minutes / 60, 2),
                'salary' => round($salary, 2),
            ];
        });

        return $schedules;
    }
}
