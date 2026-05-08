<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\Request;

class GroupController
{
    // Список групп
    public function index(Request $request)
    {
        $query = Group::with(['course'])->withCount('students');

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $groups = $query->paginate($request->get('per_page', 20));

        return GroupResource::collection($groups);
    }

    // Создание группы
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'max_students' => 'required|integer|min:1|max:30',
            'description' => 'nullable|string',
            'status' => 'in:forming,active,completed,cancelled',
        ]);

        $validated['current_students'] = 0;

        $group = Group::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Группа создана',
            'data' => new GroupResource($group)
        ], 201);
    }

    // Просмотр группы
    public function show(Group $group)
    {
        $group->load(['course', 'students.parent', 'schedules.lesson']);

        return new GroupResource($group);
    }

    // Обновление группы
    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'max_students' => 'integer|min:1|max:30',
            'description' => 'nullable|string',
            'status' => 'in:forming,active,completed,cancelled',
        ]);

        $group->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Группа обновлена',
            'data' => new GroupResource($group)
        ]);
    }

    // Удаление группы
    public function destroy(Group $group)
    {
        if ($group->students()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Нельзя удалить группу с учениками'
            ], 422);
        }

        $group->delete();

        return response()->json([
            'success' => true,
            'message' => 'Группа удалена'
        ]);
    }

    // Добавить ученика в группу
    public function addStudent(Request $request, Group $group)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::find($validated['student_id']);

        // Проверяем, не полная ли группа
        if ($group->is_full) {
            return response()->json([
                'success' => false,
                'message' => 'Группа полная'
            ], 422);
        }

        // Проверяем, не состоит ли уже
        if ($group->students()->where('student_id', $student->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ученик уже в группе'
            ], 422);
        }

        // Добавляем
        $group->students()->attach($student->id, [
            'enrolled_at' => now(),
            'status' => 'active'
        ]);

        $group->increment('current_students');

        return response()->json([
            'success' => true,
            'message' => 'Ученик добавлен в группу'
        ]);
    }

    // Удалить ученика из группы
    public function removeStudent(Group $group, Student $student)
    {
        $group->students()->updateExistingPivot($student->id, [
            'left_at' => now(),
            'status' => 'dropped'
        ]);

        $group->decrement('current_students');

        return response()->json([
            'success' => true,
            'message' => 'Ученик удалён из группы'
        ]);
    }

    // Список учеников группы
    public function students(Group $group)
    {
        $students = $group->students()->with('parent')->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }
}
