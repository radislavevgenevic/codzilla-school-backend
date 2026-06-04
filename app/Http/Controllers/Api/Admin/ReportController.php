<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Teacher;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Получить финансовые отчеты школы
     */
    public function financial(Request $request): JsonResponse
    {
        // Разрешаем фильтрацию по датам (по умолчанию за последние 12 месяцев)
        $startDateInput = $request->query('start_date');
        $endDateInput = $request->query('end_date');

        try {
            $startDate = $startDateInput ? Carbon::parse($startDateInput)->startOfDay() : Carbon::now()->subMonths(12)->startOfDay();
            $endDate = $endDateInput ? Carbon::parse($endDateInput)->endOfDay() : Carbon::now()->endOfDay();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Неверный формат даты'], 422);
        }

        // 1. Выручка от купленных абонементов
        $subscriptions = Subscription::with(['student.currentCourse', 'student.parent'])
            ->whereBetween('start_date', [$startDate, $endDate])
            ->get();

        $totalRevenue = 0;
        $revenueDetails = [];

        foreach ($subscriptions as $sub) {
            $price = floatval($sub->student?->currentCourse?->price ?? 0);
            $totalRevenue += $price;

            $revenueDetails[] = [
                'id' => $sub->id,
                'subscription_name' => $sub->name,
                'student_name' => $sub->student?->full_name ?? 'N/A',
                'parent_name' => $sub->student?->parent?->name ?? 'N/A',
                'course_name' => $sub->student?->currentCourse?->name ?? 'N/A',
                'amount' => $price,
                'start_date' => $sub->start_date?->format('Y-m-d'),
                'status' => $sub->status,
                'status_text' => match ($sub->effective_status) {
                    'active' => 'Активный',
                    'paused' => 'Приостановлен',
                    'cancelled' => 'Отменен',
                    'expired' => 'Истек',
                    default => $sub->effective_status,
                },
            ];
        }

        // 2. Расходы на зарплаты учителей (за проведенные уроки)
        $teachers = Teacher::with('user')->get();
        $totalExpenses = 0;
        $teacherExpensesDetails = [];

        foreach ($teachers as $teacher) {
            $salaryInfo = $teacher->calculateSalary($startDate, $endDate);
            $totalExpenses += $salaryInfo['total'];

            $teacherExpensesDetails[] = [
                'teacher_id' => $teacher->id,
                'name' => $teacher->user?->name ?? 'N/A',
                'rate' => floatval($teacher->hourly_rate),
                'hours' => floatval($salaryInfo['hours']),
                'lessons_count' => intval($salaryInfo['lessons_count']),
                'total_salary' => floatval($salaryInfo['total']),
            ];
        }

        $netProfit = $totalRevenue - $totalExpenses;

        // 3. Дополнительные полезные отчеты:
        // Популярность курсов (по количеству учеников)
        $courses = Course::get()->map(fn ($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'price' => floatval($c->price),
            'students_count' => \App\Models\Student::where('current_course_id', $c->id)
                ->where('status', 'active')
                ->count(),
        ])->sortByDesc('students_count')->values()->toArray();

        // Распределение статусов абонементов
        $statusBreakdown = Subscription::whereBetween('start_date', [$startDate, $endDate])
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Сводные данные по статусам для фронтенда (чтобы гарантировать наличие всех статусов в ответе)
        $formattedStatuses = [
            'active' => $statusBreakdown['active'] ?? 0,
            'paused' => $statusBreakdown['paused'] ?? 0,
            'cancelled' => $statusBreakdown['cancelled'] ?? 0,
            'expired' => $statusBreakdown['expired'] ?? 0,
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'period' => [
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                ],
                'summary' => [
                    'total_revenue' => $totalRevenue,
                    'total_expenses' => $totalExpenses,
                    'net_profit' => $netProfit,
                ],
                'revenue_details' => $revenueDetails,
                'expenses_details' => $teacherExpensesDetails,
                'course_popularity' => $courses,
                'subscriptions_status' => $formattedStatuses,
            ],
        ]);
    }
}
