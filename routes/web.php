<?php

use App\Http\Controllers\AjaxController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\MembershipController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentCardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DailyTestController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Landing & Common routes 
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // return redirect('login');
    return view('landing');
})->name('landing');

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return match (Auth::user()->role) {
        'school-admin' => app(DashboardController::class)->index(),
        'super-admin' => redirect('membership'),
        default => redirect('home'),
    };
});

/*
|--------------------------------------------------------------------------
| Super Admin routes 
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'super-admin', 'verified']], function () {

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

Route::group(['middleware' => ['auth', 'school-admin', 'verified']], function () {

    // Ajax routes
    Route::prefix('ajax')->group(function () {
        Route::get('sections/{classId}', [AjaxController::class, 'sectionsByClassId']);

        Route::get('students/{classId}/{sectionId}', [AjaxController::class, 'studentsBySectionId']);
    });

    // Class routes
    Route::prefix('class')->group(function () {
        Route::get('/', [ClassController::class, 'index']);
        Route::post('update-publication-status', [ClassController::class, 'updatePublicationStatus']);
    });

    // Section routes
    Route::prefix('section')->group(function () {
        Route::get('/', [SectionController::class, 'index']);
        Route::post('update-publication-status', [SectionController::class, 'updatePublicationStatus']);

    });

    // Subject routes
    Route::prefix('subject')->group(function () {
        Route::get('/', [SubjectController::class, 'index']);
        Route::post('update-publication-status', [SubjectController::class, 'updatePublicationStatus']);
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
        Route::post('status/{studentId}', [StudentController::class, 'updateStatus']);
        Route::get('/', [StudentController::class, 'index']);
        Route::get('upgrade-class', [StudentController::class, 'upgradeClass']);
        Route::post('upgrade-class', [StudentController::class, 'upgradeClassStore']);
        Route::get('export-csv', [StudentController::class, 'exportCsv']);
        Route::get('import', [StudentController::class, 'importForm']);
        Route::get('import-template', [StudentController::class, 'importTemplate']);
        Route::post('import', [StudentController::class, 'importStore']);
        Route::get('create', [StudentController::class, 'create']);
        Route::post('store', [StudentController::class, 'store']);

        Route::get('trash', [StudentController::class, 'trashStudents']);
        Route::get('restore-trash-student/{studentId}', [StudentController::class, 'restoreTrashStudent']);

        // Student Documents
        Route::get('documents/{id}', [StudentController::class, 'documents']);
        Route::post('documents/{id}', [StudentController::class, 'storeDocument']);

        // Student Fee History
        Route::get('{id}/fee-history', [StudentController::class, 'feeHistory']);

        // Student Attendance History
        Route::get('{id}/export-attendance-history-csv', [StudentController::class, 'exportAttendanceHistoryCsv']);

        // Student Daily Test History
        Route::get('{id}/export-daily-test-history-csv', [StudentController::class, 'exportDailyTestHistoryCsv']);

        // Student fee history export to csv
        Route::get('{id}/export-fee-history-csv', [StudentController::class, 'exportFeeHistoryCsv']);

        // Student ID card — pick design and download PNG
        Route::get('card/{id}', [StudentCardController::class, 'select']);
        Route::get('{id}/attendance-history', [StudentController::class, 'attendanceHistory']);
        Route::get('{id}/daily-test-history', [StudentController::class, 'dailyTestHistory']);
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
        Route::get('edit/{classId}/{sectionId}/{attendanceDate}', [AttendanceController::class, 'edit'])->name('attendance.edit');

        Route::get('export-to-csv/{classId}/{sectionId}/{attendanceDate}', [AttendanceController::class, 'exportToCsv'])->name('attendance.export-to-csv');
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

    // General routes
    Route::prefix('general')->group(function () {
        Route::get('/', [GeneralController::class, 'index']);
        Route::get('create', [GeneralController::class, 'create']);
        Route::post('store', [GeneralController::class, 'store']);
        Route::get('show/{id}', [GeneralController::class, 'show']);
        Route::get('edit/{id}', [GeneralController::class, 'edit']);
        Route::post('update/{id}', [GeneralController::class, 'update']);
    });

});

require __DIR__.'/fee_management.php';

Route::get('logout', function () {
    Auth::logout();
    return redirect('/login');
});

Auth::routes(['register' => true, 'verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
