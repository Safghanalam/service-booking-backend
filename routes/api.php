<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'registerLogin'])->name('auth.login');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verifyOTP');

Route::middleware('auth:sanctum')->group(function () {
    // Authentication Routes
    Route::get('/get-user', [AuthController::class, 'user'])->name('auth.getUser');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/logout-all', [AuthController::class, 'logoutAllDevice'])->name('auth.logoutAll');


    // User Routes
    Route::post('/update-user', [UserController::class, 'updateUser'])->name('user.update');
    Route::post('/delete-user', [UserController::class, 'deleteUser'])->name('user.delete');
});
