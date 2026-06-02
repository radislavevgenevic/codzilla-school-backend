<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Получить расписание преподавателя
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();

        if (!$user || !$user->isTeacher()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return response()->json(['message' => 'Teacher profile not found'], 404);
        }

        // Получаем расписание для групп этого преподавателя
        $schedules = Schedule::whereHas('group.course', function ($query) use ($teacherProfile) {
            $query->where('teacher_id', $teacherProfile->id);
        })
        ->with(['group', 'lesson'])
        ->where('start_time', '>=', now())
        ->orderBy('start_time')
        ->get()
        ->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'lesson_id' => $schedule->lesson_id,
                'lesson_name' => $schedule->lesson?->title ?? 'N/A',
                'group_id' => $schedule->group_id,
                'group_name' => $schedule->group->name,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'duration_minutes' => $schedule->getDurationInMinutes(),
                'status' => $schedule->status ?? 'scheduled',
            ];
        });

        return response()->json(['data' => $schedules]);
    }

    /**
     * Получить расписание на конкретный день
     */
    public function getByDate($date): JsonResponse
    {
        $user = auth()->user();

        if (!$user || !$user->isTeacher()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return response()->json(['message' => 'Teacher profile not found'], 404);
        }

        try {
            $date = Carbon::parse($date);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid date format'], 422);
        }

        $startOfDay = $date->clone()->startOfDay();
        $endOfDay = $date->clone()->endOfDay();

        $schedules = Schedule::whereHas('group.course', function ($query) use ($teacherProfile) {
            $query->where('teacher_id', $teacherProfile->id);
        })
        ->with(['group', 'lesson'])
        ->whereBetween('start_time', [$startOfDay, $endOfDay])
        ->orderBy('start_time')
        ->get()
        ->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'lesson_id' => $schedule->lesson_id,
                'lesson_name' => $schedule->lesson?->title ?? 'N/A',
                'group_id' => $schedule->group_id,
                'group_name' => $schedule->group->name,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'duration_minutes' => $schedule->getDurationInMinutes(),
                'status' => $schedule->status ?? 'scheduled',
            ];
        });

        return response()->json(['data' => $schedules]);
    }

    /**
     * Получить расписание за период
     */
    public function getByPeriod($startDate, $endDate): JsonResponse
    {
        $user = auth()->user();

        if (!$user || !$user->isTeacher()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return response()->json(['message' => 'Teacher profile not found'], 404);
        }

        try {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid date format'], 422);
        }

        $schedules = Schedule::whereHas('group.course', function ($query) use ($teacherProfile) {
            $query->where('teacher_id', $teacherProfile->id);
        })
        ->with(['group', 'lesson'])
        ->whereBetween('start_time', [$start, $end])
        ->orderBy('start_time')
        ->get()
        ->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'lesson_id' => $schedule->lesson_id,
                'lesson_name' => $schedule->lesson?->title ?? 'N/A',
                'group_id' => $schedule->group_id,
                'group_name' => $schedule->group->name,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'duration_minutes' => $schedule->getDurationInMinutes(),
                'status' => $schedule->status ?? 'scheduled',
            ];
        });

        return response()->json(['data' => $schedules]);
    }
}
