<?php

namespace App\Http\Controllers\Api\Parent;

use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;

class ChildController
{
    // Список детей
    public function index(Request $request)
    {
        $children = $request->user()->children()
            ->with(['currentCourse', 'groups.course'])
            ->get();

        return StudentResource::collection($children);
    }

    // Детальная инфо о ребёнке
    public function show(Request $request, $studentId)
    {
        $student = $request->user()->children()->findOrFail($studentId);

        $student->load(['groups.course', 'attendances.schedule.lesson']);

        return new StudentResource($student);
    }

    // Посещаемость ребёнка
    public function attendance(Request $request, $studentId)
    {
        $student = $request->user()->children()->findOrFail($studentId);

        $attendances = $student->attendances()
            ->with(['schedule.lesson.course', 'schedule.group'])
            ->orderBy('marked_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'student' => $student->full_name,
            'attendances' => $attendances->map(function($attendance) {
                return [
                    'date' => $attendance->schedule->start_time->format('d.m.Y'),
                    'lesson' => $attendance->schedule->lesson->title,
                    'status' => $attendance->status_text,
                    'group' => $attendance->schedule->group?->name,
                ];
            }),
            'stats' => [
                'total' => $attendances->count(),
                'present' => $attendances->where('status', 'present')->count(),
                'absent' => $attendances->where('status', 'absent')->count(),
                'late' => $attendances->where('status', 'late')->count(),
            ]
        ]);
    }
}
