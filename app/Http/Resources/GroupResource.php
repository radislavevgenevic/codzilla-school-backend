<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class GroupResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            // Основные поля
            'name' => $this->name,
            'course_id' => $this->course_id,
            'max_students' => $this->max_students,
            'current_students' => $this->current_students,
            'available_places' => $this->available_places,
            'is_full' => $this->is_full,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'description' => $this->description,

            // Связи (загружаются по требованию)
            'course' => new CourseResource($this->whenLoaded('course')),
            'students' => StudentResource::collection($this->whenLoaded('students')),
            'schedules' => ScheduleResource::collection($this->whenLoaded('schedules')),

            // Статистика (counts)
            'students_count' => $this->whenCounted('students'),
            'active_students_count' => $this->whenLoaded('activeStudents', function() {
                return $this->activeStudents()->count();
            }),
            'schedules_count' => $this->whenCounted('schedules'),

            // Ближайшее занятие
            'next_schedule' => $this->whenLoaded('schedules', function() {
                $nextSchedule = $this->schedules
                    ->where('start_time', '>', now())
                    ->sortBy('start_time')
                    ->first();

                return $nextSchedule ? [
                    'id' => $nextSchedule->id,
                    'start_time' => $nextSchedule->start_time?->toISOString(),
                    'start_time_formatted' => $nextSchedule->start_time?->format('d.m.Y H:i'),
                    'lesson_title' => $nextSchedule->lesson?->title,
                ] : null;
            }),

            // Для админа: редактирование
            'can_edit' => $request->user()?->isAdmin(),
            'can_delete' => $request->user()?->isAdmin() && $this->students()->count() === 0,
        ]);
    }

    public static function collection($resource)
    {
        return parent::collection($resource)->additional([
            'stats' => [
                'total_groups' => $resource->total() ?? $resource->count(),
                'forming' => $resource->where('status', 'forming')->count(),
                'active' => $resource->where('status', 'active')->count(),
                'completed' => $resource->where('status', 'completed')->count(),
                'cancelled' => $resource->where('status', 'cancelled')->count(),
            ],
        ]);
    }
}
