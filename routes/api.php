<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
// Trainer
use App\Http\Controllers\Api\Trainer\FoodController;
use App\Http\Controllers\Api\Trainer\ProgramController;
use App\Http\Controllers\Api\Trainer\ItemsLatihanController;
use App\Http\Controllers\Api\Trainer\ProfileController as TrainerProfileController;
// Client
use App\Http\Controllers\Api\Client\DiaryController;
use App\Http\Controllers\Api\Client\HistoryController;
use App\Http\Controllers\Api\Client\StatisticController;
use App\Http\Controllers\Api\Client\ExerciseController;
use App\Http\Controllers\Api\Client\ProfileController as ClientProfileController;


// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register/client', [AuthController::class, 'registerClient']);
Route::post('/register/trainer', [AuthController::class, 'registerTrainer']);

// Default user route from Laravel 11
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Trainer Routes (role:2)
    Route::middleware('role:2')->prefix('trainer')->name('api.trainer.')->group(function () {
        
        // Profile Trainer
        Route::get('/profile', [TrainerProfileController::class, 'show']);
        Route::post('/profile', [TrainerProfileController::class, 'update']); // Menggunakan POST untuk multipart/form-data (upload file)
        
        // CRUD Makanan & Program
        Route::apiResource('foods', FoodController::class);
        Route::apiResource('programs', ProgramController::class);
        Route::apiResource('programs.items', ItemsLatihanController::class)->shallow();
        
    });

    // Client Routes (role:1)
    Route::middleware('role:1')->prefix('client')->name('api.client.')->group(function () {
        
        // Profile Client
        Route::get('/profile', [ClientProfileController::class, 'show']);
        Route::post('/profile', [ClientProfileController::class, 'update']); // POST karena upload avatar

        // Diary
        Route::get('/diary', [DiaryController::class, 'index']);
        Route::post('/diary', [DiaryController::class, 'store']);
        Route::delete('/diary', [DiaryController::class, 'destroy']);

        // History
        Route::get('/history', [HistoryController::class, 'index']);

        // Statistic
        Route::get('/statistic', [StatisticController::class, 'index']);

        // Exercise
        Route::get('/exercise', [ExerciseController::class, 'index']);
        Route::get('/exercise/{id}', [ExerciseController::class, 'show']);
        Route::post('/exercise/{id}/start', [ExerciseController::class, 'start']);

    });
});
