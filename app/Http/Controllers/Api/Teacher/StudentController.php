<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Group;

class StudentController extends Controller
{
    /**
     * Получить всех учеников преподавателя
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

        // Получаем все группы, принадлежащие курсам этого преподавателя
        $groups = Group::whereHas('course', function ($query) use ($teacherProfile) {
            $query->where('teacher_id', $teacherProfile->id);
        })
        ->with('students.parent')
        ->get();

        $students = collect();

        foreach ($groups as $group) {
            foreach ($group->students as $student) {
                // Избегаем дублирования
                if (!$students->contains('id', $student->id)) {
                    $students->push([
                        'id' => $student->id,
                        'name' => $student->full_name,
                        'full_name' => $student->full_name,
                        'parent_id' => $student->parent_id,
                        'phone' => $student->parent?->phone,
                        'parent_name' => $student->parent?->name ?? 'N/A',
                        'parent_phone' => $student->parent?->phone ?? 'N/A',
                        'group_id' => $group->id,
                        'group_name' => $group->name,
                    ]);
                }
            }
        }

        return response()->json(['data' => $students->values()]);
    }

    /**
     * Получить учеников конкретной группы
     */
    public function showByGroup($groupId): JsonResponse
    {
        $user = auth()->user();

        if (!$user || !$user->isTeacher()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return response()->json(['message' => 'Teacher profile not found'], 404);
        }

        // Проверяем, что группа принадлежит курсу этого преподавателя
        $group = Group::whereHas('course', function ($query) use ($teacherProfile) {
            $query->where('teacher_id', $teacherProfile->id);
        })
        ->with('students.parent')
        ->find($groupId);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        $students = $group->students->map(function ($student) use ($group) {
            return [
                'id' => $student->id,
                'name' => $student->full_name,
                'full_name' => $student->full_name,
                'parent_id' => $student->parent_id,
                'phone' => $student->parent?->phone,
                'parent_name' => $student->parent?->name ?? 'N/A',
                'parent_phone' => $student->parent?->phone ?? 'N/A',
                'group_id' => $group->id,
                'group_name' => $group->name,
            ];
        });

        return response()->json(['data' => $students]);
    }
}
