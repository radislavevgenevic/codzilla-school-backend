<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    /**
     * Получить все курсы преподавателя
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

        $courses = $teacherProfile->courses()
            ->with(['groups.students', 'lessons'])
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'slug' => $course->slug,
                    'icon' => $course->icon,
                    'age_range' => $course->age_range,
                    'description' => $course->description,
                    'price' => $course->price,
                    'duration_weeks' => $course->duration_weeks,
                    'is_active' => $course->is_active,
                    'groups_count' => $course->groups->count(),
                    'students_count' => $course->groups->sum(function ($group) {
                        return $group->students->count();
                    }),
                    'lessons_count' => $course->lessons->count(),
                ];
            });

        return response()->json(['data' => $courses]);
    }

    /**
     * Получить конкретный курс преподавателя
     */
    public function show($courseId): JsonResponse
    {
        $user = auth()->user();

        if (!$user || !$user->isTeacher()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return response()->json(['message' => 'Teacher profile not found'], 404);
        }

        $course = $teacherProfile->courses()
            ->with(['groups.students', 'lessons.schedules'])
            ->find($courseId);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return response()->json(['data' => $course]);
    }
}
