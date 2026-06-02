<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Public\CourseController as PublicCourseController;
use App\Http\Controllers\Api\Public\ApplicationController;
use App\Http\Controllers\Api\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Api\Admin\GroupController;
use App\Http\Controllers\Api\Admin\AttendanceController;
use App\Http\Controllers\Api\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Api\Admin\NotificationSettingController;
use App\Http\Controllers\Api\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Api\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Api\Parent\ChildController;
use App\Http\Controllers\Api\Parent\SubscriptionController as ParentSubscriptionController;
use App\Http\Controllers\Api\Public\StatisticsController;
use App\Http\Controllers\Api\Teacher\CourseController as TeacherCourseController;
use App\Http\Controllers\Api\Teacher\StudentController as TeacherStudentController;
use App\Http\Controllers\Api\Teacher\ScheduleController;
use App\Http\Controllers\Api\Teacher\SalaryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public routes
    Route::prefix('public')->group(function () {
        Route::get('/courses', [PublicCourseController::class, 'index']);
        Route::get('/courses/{slug}', [PublicCourseController::class, 'show']);
        Route::get('/courses/{slug}/lessons', [PublicCourseController::class, 'lessons']);
        Route::post('/applications', [ApplicationController::class, 'store']);
        Route::post('/feedback', [ApplicationController::class, 'feedback']);
        Route::get('/statistics', [StatisticsController::class, 'index']);
    });

    // Auth
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Admin panel
        Route::middleware('admin')->prefix('admin')->group(function () {

            // Courses
            Route::get('/courses/icons', [AdminCourseController::class, 'getIcons']);
            Route::apiResource('courses', AdminCourseController::class);

            // Groups
            Route::apiResource('groups', GroupController::class);
            Route::post('/groups/{group}/add-student', [GroupController::class, 'addStudent']);
            Route::delete('/groups/{group}/remove-student/{student}', [GroupController::class, 'removeStudent']);
            Route::get('/groups/{group}/students', [GroupController::class, 'students']);

            // Users
            Route::apiResource('users', AdminUserController::class);

            // Teachers
            Route::apiResource('teachers', AdminTeacherController::class);
            Route::post('/teachers/{teacher}/assign-courses', [AdminTeacherController::class, 'assignCourses']);
            Route::post('/teachers/{teacher}/unassign-course', [AdminTeacherController::class, 'unassignCourse']);

            // Students
            Route::apiResource('students', AdminStudentController::class);

            // Subscriptions
            Route::apiResource('subscriptions', AdminSubscriptionController::class);
            Route::post('/subscriptions/{subscription}/extend', [AdminSubscriptionController::class, 'extend']);

            // Lessons with group schedule
            Route::get('/lessons', [AdminLessonController::class, 'index']);
            Route::post('/lessons', [AdminLessonController::class, 'store']);
            Route::get('/lessons/{schedule}', [AdminLessonController::class, 'show']);
            Route::put('/lessons/{schedule}', [AdminLessonController::class, 'update']);
            Route::patch('/lessons/{schedule}', [AdminLessonController::class, 'update']);
            Route::delete('/lessons/{schedule}', [AdminLessonController::class, 'destroy']);

            // Notification settings
            Route::get('/notification-settings', [NotificationSettingController::class, 'show']);
            Route::put('/notification-settings', [NotificationSettingController::class, 'update']);

            // Attendance
            Route::get('/attendance/schedule/{schedule}', [AttendanceController::class, 'getScheduleForMarking']);
            Route::post('/attendance/schedule/{schedule}/mark', [AttendanceController::class, 'markAttendance']);
            Route::get('/attendance/student/{student}', [AttendanceController::class, 'studentHistory']);

        });

        // Parent account
        Route::middleware('parent')->prefix('parent')->group(function () {
            Route::get('/children', [ChildController::class, 'index']);
            Route::get('/children/{student}', [ChildController::class, 'show']);
            Route::get('/children/{student}/attendance', [ChildController::class, 'attendance']);
            Route::get('/subscriptions', [ParentSubscriptionController::class, 'index']);
            Route::get('/subscriptions/{subscription}', [ParentSubscriptionController::class, 'show']);
        });

        // Teacher account
        Route::middleware('teacher')->prefix('teacher')->group(function () {
            // Courses
            Route::get('/courses', [TeacherCourseController::class, 'index']);
            Route::get('/courses/{courseId}', [TeacherCourseController::class, 'show']);

            // Students
            Route::get('/students', [TeacherStudentController::class, 'index']);
            Route::get('/students/group/{groupId}', [TeacherStudentController::class, 'showByGroup']);

            // Schedule
            Route::get('/schedule', [ScheduleController::class, 'index']);
            Route::get('/schedule/date/{date}', [ScheduleController::class, 'getByDate']);
            Route::get('/schedule/period/{startDate}/{endDate}', [ScheduleController::class, 'getByPeriod']);

            // Salary
            Route::get('/salary/daily', [SalaryController::class, 'getDaily']);
            Route::get('/salary/daily/{date}', [SalaryController::class, 'getDaily']);
            Route::get('/salary/weekly', [SalaryController::class, 'getWeekly']);
            Route::get('/salary/weekly/{date}', [SalaryController::class, 'getWeekly']);
            Route::get('/salary/monthly', [SalaryController::class, 'getMonthly']);
            Route::get('/salary/monthly/{date}', [SalaryController::class, 'getMonthly']);
        });

    });
});
