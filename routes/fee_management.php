<?php

use App\Http\Controllers\FeeManagementController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'school-admin'], 'prefix' => 'fee-management'], function () {
    Route::get('/', [FeeManagementController::class, 'index']);
    Route::get('show/{classId}', [FeeManagementController::class, 'show']);
    Route::post('store', [FeeManagementController::class, 'store']);
});
