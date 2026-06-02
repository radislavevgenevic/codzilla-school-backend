<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class SalaryController extends Controller
{
    /**
     * Получить зарплату за день
     */
    public function getDaily($date = null): JsonResponse
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
            $date = $date ? Carbon::parse($date) : now();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid date format'], 422);
        }

        $salary = $teacherProfile->calculateSalary(
            $date->clone()->startOfDay(),
            $date->clone()->endOfDay()
        );

        $details = $teacherProfile->getSalaryDetails(
            $date->clone()->startOfDay(),
            $date->clone()->endOfDay()
        );

        return response()->json([
            'data' => [
                'date' => $date->toDateString(),
                'salary' => $salary['total'],
                'hours' => $salary['hours'],
                'minutes' => $salary['minutes'],
                'rate' => $salary['rate'],
                'lessons_count' => $salary['lessons_count'],
                'details' => $details,
            ]
        ]);
    }

    /**
     * Получить зарплату за неделю
     */
    public function getWeekly($date = null): JsonResponse
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
            $date = $date ? Carbon::parse($date) : now();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid date format'], 422);
        }

        $startOfWeek = $date->clone()->startOfWeek();
        $endOfWeek = $date->clone()->endOfWeek();

        $salary = $teacherProfile->calculateSalary($startOfWeek, $endOfWeek);

        $details = $teacherProfile->getSalaryDetails($startOfWeek, $endOfWeek);

        return response()->json([
            'data' => [
                'week_start' => $startOfWeek->toDateString(),
                'week_end' => $endOfWeek->toDateString(),
                'salary' => $salary['total'],
                'hours' => $salary['hours'],
                'minutes' => $salary['minutes'],
                'rate' => $salary['rate'],
                'lessons_count' => $salary['lessons_count'],
                'details' => $details,
            ]
        ]);
    }

    /**
     * Получить зарплату за месяц
     */
    public function getMonthly($date = null): JsonResponse
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
            $date = $date ? Carbon::parse($date) : now();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid date format'], 422);
        }

        $startOfMonth = $date->clone()->startOfMonth();
        $endOfMonth = $date->clone()->endOfMonth();

        $salary = $teacherProfile->calculateSalary($startOfMonth, $endOfMonth);

        $details = $teacherProfile->getSalaryDetails($startOfMonth, $endOfMonth);

        return response()->json([
            'data' => [
                'month' => $date->format('Y-m'),
                'month_name' => $date->locale('ru')->translatedFormat('F Y'),
                'salary' => $salary['total'],
                'hours' => $salary['hours'],
                'minutes' => $salary['minutes'],
                'rate' => $salary['rate'],
                'lessons_count' => $salary['lessons_count'],
                'details' => $details,
            ]
        ]);
    }
}
