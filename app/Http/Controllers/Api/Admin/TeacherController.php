<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Получить всех преподавателей
     */
    public function index(): JsonResponse
    {
        $teachers = Teacher::with('user')
            ->withCount('courses')
            ->get()
            ->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'user_id' => $teacher->user_id,
                    'name' => $teacher->user->name,
                    'email' => $teacher->user->email,
                    'phone' => $teacher->user->phone,
                    'hourly_rate' => $teacher->hourly_rate,
                    'bio' => $teacher->bio,
                    'courses_count' => $teacher->courses_count,
                    'is_active' => $teacher->user->is_active,
                ];
            });

        return response()->json([
            'data' => $teachers,
            'meta' => [
                'total' => $teachers->count(),
            ]
        ]);
    }

    /**
     * Получить конкретного преподавателя
     */
    public function show($id): JsonResponse
    {
        $teacher = Teacher::with(['user', 'courses'])
            ->find($id);

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $teacher->id,
                'user_id' => $teacher->user_id,
                'name' => $teacher->user->name,
                'email' => $teacher->user->email,
                'phone' => $teacher->user->phone,
                'hourly_rate' => $teacher->hourly_rate,
                'bio' => $teacher->bio,
                'is_active' => $teacher->user->is_active,
                'courses' => $teacher->courses->map(fn ($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                ]),
            ]
        ]);
    }

    /**
     * Создать нового преподавателя
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'hourly_rate' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
        ]);

        // Создаем пользователя с ролью teacher
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'role' => 'teacher',
            'is_active' => true,
        ]);

        // Создаем профиль преподавателя
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'hourly_rate' => $validated['hourly_rate'],
            'bio' => $validated['bio'] ?? null,
        ]);

        return response()->json([
            'data' => [
                'id' => $teacher->id,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'hourly_rate' => $teacher->hourly_rate,
                'bio' => $teacher->bio,
                'is_active' => $user->is_active,
            ]
        ], 201);
    }

    /**
     * Обновить преподавателя
     */
    public function update(Request $request, $id): JsonResponse
    {
        $teacher = Teacher::with('user')->find($id);

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $teacher->user_id,
            'phone' => 'sometimes|nullable|string|max:20|unique:users,phone,' . $teacher->user_id,
            'password' => 'sometimes|string|min:8|confirmed',
            'hourly_rate' => 'sometimes|numeric|min:0',
            'bio' => 'sometimes|nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        // Обновляем данные пользователя
        $userData = [];
        if (isset($validated['name'])) $userData['name'] = $validated['name'];
        if (isset($validated['email'])) $userData['email'] = $validated['email'];
        if (isset($validated['phone'])) $userData['phone'] = $validated['phone'];
        if (isset($validated['password'])) $userData['password'] = Hash::make($validated['password']);
        if (isset($validated['is_active'])) $userData['is_active'] = $validated['is_active'];

        if (!empty($userData)) {
            $teacher->user->update($userData);
        }

        // Обновляем данные преподавателя
        $teacherData = [];
        if (isset($validated['hourly_rate'])) $teacherData['hourly_rate'] = $validated['hourly_rate'];
        if (isset($validated['bio'])) $teacherData['bio'] = $validated['bio'];

        if (!empty($teacherData)) {
            $teacher->update($teacherData);
        }

        $teacher->refresh();
        $teacher->load('user');

        return response()->json([
            'data' => [
                'id' => $teacher->id,
                'user_id' => $teacher->user_id,
                'name' => $teacher->user->name,
                'email' => $teacher->user->email,
                'phone' => $teacher->user->phone,
                'hourly_rate' => $teacher->hourly_rate,
                'bio' => $teacher->bio,
                'is_active' => $teacher->user->is_active,
            ]
        ]);
    }

    /**
     * Удалить преподавателя
     */
    public function destroy($id): JsonResponse
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        // Удаляем связь teacher_id в курсах
        Course::where('teacher_id', $teacher->id)->update(['teacher_id' => null]);

        // Удаляем преподавателя (это также удалит пользователя благодаря onDelete cascade)
        $user = $teacher->user;
        $teacher->delete();
        $user->delete();

        return response()->json([
            'message' => 'Teacher deleted successfully'
        ]);
    }

    /**
     * Назначить курсы преподавателю
     */
    public function assignCourses(Request $request, $id): JsonResponse
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        $validated = $request->validate([
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        // Обновляем курсы
        foreach ($validated['course_ids'] as $courseId) {
            Course::find($courseId)->update(['teacher_id' => $teacher->id]);
        }

        return response()->json([
            'message' => 'Courses assigned successfully',
            'courses_count' => count($validated['course_ids']),
        ]);
    }

    /**
     * Отменить назначение курса преподавателю
     */
    public function unassignCourse(Request $request, $id): JsonResponse
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::find($validated['course_id']);

        if ($course->teacher_id !== $teacher->id) {
            return response()->json(['message' => 'Course not assigned to this teacher'], 400);
        }

        $course->update(['teacher_id' => null]);

        return response()->json([
            'message' => 'Course unassigned successfully',
        ]);
    }
}
