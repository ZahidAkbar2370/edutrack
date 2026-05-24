<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\MembershipController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DailyTestController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Common routes 
|--------------------------------------------------------------------------
*/

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });



/*
|--------------------------------------------------------------------------
| Super Admin routes 
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'super-admin']], function () {
    // Membership routes
    Route::prefix('membership')->group(function () {    
        Route::get('/', [MembershipController::class, 'index']);
        Route::get('create', [MembershipController::class, 'create']);
        Route::post('store', [MembershipController::class, 'store']);
        Route::get('show/{id}', [MembershipController::class, 'show']);
        Route::get('edit/{id}', [MembershipController::class, 'edit']);
        Route::post('update/{id}', [MembershipController::class, 'update']);
        Route::delete('delete/{id}', [MembershipController::class, 'destroy']);
    });

    // School routes
    Route::prefix('school')->group(function () {
        Route::get('/', [SchoolController::class, 'index']);
        Route::get('create', [SchoolController::class, 'create']);
        Route::post('store', [SchoolController::class, 'store']);
        Route::get('show/{id}', [SchoolController::class, 'show']);
        Route::get('edit/{id}', [SchoolController::class, 'edit']);
        Route::post('update/{id}', [SchoolController::class, 'update']);
        Route::delete('delete/{id}', [SchoolController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| School routes 
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'school-admin']], function () {
    // Class routes
    Route::prefix('class')->group(function () {
        Route::get('/', [ClassController::class, 'index']);
        Route::get('create', [ClassController::class, 'create']);
        Route::post('store', [ClassController::class, 'store']);
        Route::get('show/{id}', [ClassController::class, 'show']);
        Route::get('edit/{id}', [ClassController::class, 'edit']);
        Route::post('update/{id}', [ClassController::class, 'update']);
        Route::delete('delete/{id}', [ClassController::class, 'destroy']);
    });

    // Section routes
    Route::prefix('section')->group(function () {
        Route::get('/', [SectionController::class, 'index']);
        Route::get('create', [SectionController::class, 'create']);
        Route::post('store', [SectionController::class, 'store']);
        Route::get('show/{id}', [SectionController::class, 'show']);
        Route::get('edit/{id}', [SectionController::class, 'edit']);
        Route::post('update/{id}', [SectionController::class, 'update']);
        Route::delete('delete/{id}', [SectionController::class, 'destroy']);
    });

    // Subject routes
    Route::prefix('subject')->group(function () {
        Route::get('/', [SubjectController::class, 'index']);
        Route::get('create', [SubjectController::class, 'create']);
        Route::post('store', [SubjectController::class, 'store']);
        Route::get('show/{id}', [SubjectController::class, 'show']);
        Route::get('edit/{id}', [SubjectController::class, 'edit']);
        Route::post('update/{id}', [SubjectController::class, 'update']);
        Route::delete('delete/{id}', [SubjectController::class, 'destroy']);
    });

    // Teacher routes
    Route::prefix('teacher')->group(function () {
        Route::get('/', [TeacherController::class, 'index']);
        Route::get('create', [TeacherController::class, 'create']);
        Route::post('store', [TeacherController::class, 'store']);
        Route::get('show/{id}', [TeacherController::class, 'show']);
        Route::get('edit/{id}', [TeacherController::class, 'edit']);
        Route::post('update/{id}', [TeacherController::class, 'update']);
        Route::delete('delete/{id}', [TeacherController::class, 'destroy']);
    });

    // Student routes
    Route::prefix('student')->group(function () {
        Route::get('/', [StudentController::class, 'index']);
        Route::get('create', [StudentController::class, 'create']);
        Route::post('store', [StudentController::class, 'store']);
        Route::get('show/{id}', [StudentController::class, 'show']);
        Route::get('edit/{id}', [StudentController::class, 'edit']);
        Route::post('update/{id}', [StudentController::class, 'update']);
        Route::delete('delete/{id}', [StudentController::class, 'destroy']);
    });

    // Attendance routes
    Route::prefix('attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('create', [AttendanceController::class, 'create']);
        Route::get('students-list', [AttendanceController::class, 'studentsList']);
        Route::post('store', [AttendanceController::class, 'store']);
        Route::get('show', [AttendanceController::class, 'show'])->name('attendance.show');
        Route::post('update', [AttendanceController::class, 'update']);
    });

    // Daily test routes
    Route::prefix('daily-test')->group(function () {
        Route::get('/', [DailyTestController::class, 'index'])->name('daily-test.index');
        Route::get('create', [DailyTestController::class, 'create']);
        Route::get('students-list', [DailyTestController::class, 'studentsList']);
        Route::post('store', [DailyTestController::class, 'store']);
        Route::get('show', [DailyTestController::class, 'show'])->name('daily-test.show');
        Route::post('update', [DailyTestController::class, 'update']);
    });

    Route::get('logout', function () {
        Auth::logout();
        return redirect('/login');
    });
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
