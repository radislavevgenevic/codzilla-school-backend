<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AttendanceResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            // Основные поля
            'schedule_id' => $this->schedule_id,
            'student_id' => $this->student_id,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'reason' => $this->reason,
            'marked_by' => $this->marked_by,
            'marked_at' => $this->marked_at?->toISOString(),
            'marked_at_formatted' => $this->marked_at?->format('d.m.Y H:i'),

            // Связи
            'schedule' => new ScheduleResource($this->whenLoaded('schedule')),
            'student' => new StudentResource($this->whenLoaded('student')),
            'marker' => new UserResource($this->whenLoaded('markedBy')),

            // Вычисляемые поля
            'was_late' => $this->status === 'late',
            'was_absent' => $this->status === 'absent',
            'was_present' => $this->status === 'present',

            // Для родителя: контекст группы
            'group_name' => $this->whenLoaded('schedule', function() {
                return $this->schedule->group?->name ?? null;
            }),

            'lesson_title' => $this->whenLoaded('schedule', function() {
                return $this->schedule->lesson?->title ?? null;
            }),
        ]);
    }

    public static function collection($resource)
    {
        return parent::collection($resource)->additional([
            'summary' => [
                'present_count' => $resource->where('status', 'present')->count(),
                'absent_count' => $resource->where('status', 'absent')->count(),
                'late_count' => $resource->where('status', 'late')->count(),
                'total_count' => $resource->count(),
                'attendance_rate' => $resource->count() > 0
                    ? round(($resource->where('status', 'present')->count() / $resource->count()) * 100, 2)
                    : 0,
            ],
        ]);
    }
}
