<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientRegisterController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProfilTrainerController;
use App\Http\Controllers\ItemsLatihanController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\DiaryController;
use App\Http\Controllers\Client\HistoryController;
use App\Http\Controllers\Client\StatisticController;
use App\Http\Controllers\Client\ExerciseController;



Route::get("/", function () {
    return view('home');
})->name('home');

Route::get('/login/user', [AuthController::class, 'showLoginUser'])->name('login.user');
Route::get('/login/trainer', [AuthController::class, 'showLoginTrainer'])->name('login.trainer');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.do');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'doRegister'])->name('register.do');

Route::get('/register/client', [ClientRegisterController::class, 'show'])->name('register.client.show');
Route::post('/register/client', [ClientRegisterController::class, 'store'])->name('register.client.store');

Route::prefix('trainer')->name('trainer.')->group(function () {
    Route::resource('foods', FoodController::class);
    Route::resource('programs', ProgramController::class);
    Route::resource('programs.items', ItemsLatihanController::class)->shallow();
});


// Role-specific example pages
Route::get('/user/home', function () {
    return view('user_home');
})->name('user.home')->middleware(['auth.required', 'role:1']);

Route::get('/trainer/home', function () {
    return view('trainer_home');
})->name('trainer.home')->middleware(['auth.required', 'role:2']);

// Profile trainer routes
Route::prefix('profile/trainer')->group(function () {

    Route::get('/', [ProfilTrainerController::class, 'showTrainer'])
        ->name('profile.trainer.show');

    Route::post('/', [ProfilTrainerController::class, 'updateTrainer'])
        ->name('profile.trainer.update');
});

// Profile client routes
Route::prefix('profile/client')->group(function () {

    Route::get('/', [ProfileController::class, 'show'])
        ->name('profile.client.show');

    Route::post('/', [ProfileController::class, 'update'])
        ->name('profile.client.update');
});




Route::get('/client/home', function () {
    return view('client_home');
})->name('client.home')->middleware(['auth.required', 'role:1']);

Route::prefix('client')->name('client.')->middleware(['auth.required', 'role:1'])->group(function () {
    Route::get('/diary', [DiaryController::class, 'index'])->name('diary');
    Route::get('/diary/add/{category}', [DiaryController::class, 'showAddFood'])->name('diary.add');
    Route::post('/diary/store', [DiaryController::class, 'storeFood'])->name('diary.store');
    Route::delete('/diary/remove', [DiaryController::class, 'removeFood'])->name('diary.remove');

    Route::get('/history', [HistoryController::class, 'index'])->name('history');

    Route::get('/statistic', [StatisticController::class, 'index'])->name('statistic');

    Route::get('/exercise', [ExerciseController::class, 'index'])->name('exercise');
    Route::get('/exercise/{id}', [ExerciseController::class, 'show'])->name('exercise.show');
    Route::post('/exercise/{id}/start', [ExerciseController::class, 'start'])->name('exercise.start');
});