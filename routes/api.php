<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'registerLogin'])->name('auth.login');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verifyOTP');

// Categories Routes
Route::get('/active-categories', [CategoryController::class, 'getActiveCategories']);
Route::get('/parent-categories', [CategoryController::class, 'getParentCategories']);
Route::get('/subcategories', [CategoryController::class, 'getSubCategories']);
Route::get('/get-subcategories', [CategoryController::class, 'getCategoriesByParentId']);

// Employee
Route::get('/get-associated-employees', [CategoryController::class, 'getAssociatedEmployees']);

Route::middleware('auth:sanctum')->group(function () {
    // Authentication Routes
    Route::get('/get-user', [AuthController::class, 'user'])->name('auth.getUser');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/logout-all', [AuthController::class, 'logoutAllDevice'])->name('auth.logoutAll');


    // User Routes
    Route::post('/update-user', [UserController::class, 'updateUser'])->name('user.update');
    Route::delete('/delete-user', [UserController::class, 'deleteUser'])->name('user.delete');


    // Admin Protected Routes
    Route::middleware(['isAdmin'])->group(function () {
        // Categories
        Route::get('/admin/all-categories', [CategoryController::class, 'getAllCategories']);
        Route::get('/admin/all-subcategories', [CategoryController::class, 'getAllSubCategories']);
        Route::post('/admin/update-category', [CategoryController::class, 'updateCategory']);
        Route::post('/admin/add-category', [CategoryController::class, 'addCategory']);
        Route::delete('/admin/delete-category', [CategoryController::class, 'deleteCategory']);

        // Employee
        Route::get('/admin/add-employee', [CategoryController::class, 'store']);
        Route::get('/admin/update-employee', [CategoryController::class, 'update']);
        Route::get('/admin/delete-employee', [CategoryController::class, 'destroy']);
    });
});
