<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'super-admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'school-admin' => \App\Http\Middleware\SchoolAdminMiddleware::class,
            'attendance' => \App\Http\Middleware\AttendanceMiddleware::class,
            'daily-test' => \App\Http\Middleware\DailyTestMiddleware::class,
            'student-card' => \App\Http\Middleware\StudentCardMiddleware::class,
            'teacher-limit' => \App\Http\Middleware\TeacherLimitMiddleware::class,
            'student-limit' => \App\Http\Middleware\StudentLimitMiddleware::class,
            'fee-management' => \App\Http\Middleware\FeeManagementMiddleware::class,
            'membership-plan' => \App\Http\Middleware\MembershipPlanMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
