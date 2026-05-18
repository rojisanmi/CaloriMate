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

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register/client', [AuthController::class, 'registerClient']);
Route::post('/register/trainer', [AuthController::class, 'registerTrainer']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CURRENT USER
    |--------------------------------------------------------------------------
    */

    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | TRAINER ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:2')
        ->prefix('trainer')
        ->name('api.trainer.')
        ->group(function () {

            // Profile Trainer
            Route::get('/profile', [TrainerProfileController::class, 'show']);
            Route::post('/profile', [TrainerProfileController::class, 'update']);

            // Foods
            Route::apiResource('foods', FoodController::class);

            // Programs
            Route::apiResource('programs', ProgramController::class);

            // Program Items
            Route::apiResource('programs.items', ItemsLatihanController::class)
                ->shallow();
        });

    /*
    |--------------------------------------------------------------------------
    | CLIENT ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:1')
        ->prefix('client')
        ->name('api.client.')
        ->group(function () {

            // Profile Client
            Route::get('/profile', [ClientProfileController::class, 'show']);
            Route::post('/profile', [ClientProfileController::class, 'update']);

            /*
            |--------------------------------------------------------------------------
            | DIARY
            |--------------------------------------------------------------------------
            */

            Route::get('/diary', [DiaryController::class, 'index']);
            Route::post('/diary', [DiaryController::class, 'store']);
            Route::delete('/diary', [DiaryController::class, 'destroy']);

            /*
            |--------------------------------------------------------------------------
            | HISTORY
            |--------------------------------------------------------------------------
            */

            Route::get('/history', [HistoryController::class, 'index']);

            /*
            |--------------------------------------------------------------------------
            | STATISTIC
            |--------------------------------------------------------------------------
            */

            Route::get('/statistic', [StatisticController::class, 'index']);

            /*
            |--------------------------------------------------------------------------
            | EXERCISE
            |--------------------------------------------------------------------------
            */

            Route::get('/exercise', [ExerciseController::class, 'index']);
            Route::get('/exercise/{id}', [ExerciseController::class, 'show']);
            Route::post('/exercise/{id}/start', [ExerciseController::class, 'start']);

            /*
            |--------------------------------------------------------------------------
            | FOODS
            |--------------------------------------------------------------------------
            */

            Route::get('/foods', [\App\Http\Controllers\Api\Trainer\FoodController::class, 'index']);
        });
});