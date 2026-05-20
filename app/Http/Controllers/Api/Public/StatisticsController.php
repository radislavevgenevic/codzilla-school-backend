<?php

namespace App\Http\Controllers\Api\Public;

use App\Models\Course;
use App\Models\Group;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticsController
{
    /**
     * Получить общую статистику школы
     */
    public function index(Request $request)
    {
        // Основная статистика
        $stats = [
            'total_students' => Student::where('status', 'active')->count(),
            'total_courses' => Course::where('is_active', true)->count(),
            'total_groups' => Group::where('status', 'active')->count(),
            'total_parents' => User::where('role', 'parent')->count(),
            'total_graduates' => Student::where('status', 'graduated')->count(),
        ];

        // Дополнительная статистика (по желанию)
        $stats['additional'] = [
            'students_by_gender' => [
                'male' => Student::where('gender', 'male')->count(),
                'female' => Student::where('gender', 'female')->count(),
            ],
            'courses_by_age' => $this->getCoursesByAgeGroup(),
            'average_students_per_course' => $this->getAverageStudentsPerCourse(),
        ];

        // Добавляем общее количество если нужно
        if ($request->get('include_total', false)) {
            $stats['total_all_time'] = [
                'students' => Student::count(),
                'courses' => Course::count(),
                'groups' => Group::count(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $stats,
            'meta' => [
                'updated_at' => now()->toISOString(),
                'version' => '1.0',
            ],
        ]);
    }

    /**
     * Получить количество курсов по возрастным группам
     */
    private function getCoursesByAgeGroup(): array
    {
        $ageGroups = [
            '3-6' => ['min' => 3, 'max' => 6, 'label' => 'Дошкольники (3-6 лет)'],
            '7-10' => ['min' => 7, 'max' => 10, 'label' => 'Младшая школа (7-10 лет)'],
            '11-14' => ['min' => 11, 'max' => 14, 'label' => 'Средняя школа (11-14 лет)'],
            '15-18' => ['min' => 15, 'max' => 18, 'label' => 'Старшая школа (15-18 лет)'],
        ];

        $courses = Course::where('is_active', true)->get();

        $result = [];
        foreach ($ageGroups as $key => $group) {
            $count = 0;
            foreach ($courses as $course) {
                // Курс подходит для возрастной группы, если диапазоны пересекаются
                $courseMin = $course->age_from;
                $courseMax = $course->age_to;
                $groupMin = $group['min'];
                $groupMax = $group['max'];

                // Проверка на пересечение диапазонов
                if ($courseMin <= $groupMax && $courseMax >= $groupMin) {
                    $count++;
                }
            }
            $result[$key] = $count;
        }

        return $result;
    }

    /**
     * Получить среднее количество учеников на курс
     */
    private function getAverageStudentsPerCourse(): float
    {
        $courses = Course::withCount('students')->get();
        $totalStudents = $courses->sum('students_count');
        $totalCourses = $courses->count();

        if ($totalCourses === 0) {
            return 0;
        }

        return round($totalStudents / $totalCourses, 2);
    }
}
