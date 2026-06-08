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
use App\Http\Controllers\Client\NotificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TrainerHomeController;
use App\Http\Controllers\Client\HomeController;



Route::get("/", function () {
    return view('home');
})->name('home');

Route::get('/login/client', [AuthController::class, 'showLoginClient'])->name('login.client');
Route::get('/login/trainer', [AuthController::class, 'showLoginTrainer'])->name('login.trainer');
Route::get('/login/admin', [AuthController::class, 'showLoginAdmin'])->name('login.admin');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.do');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterChoice'])->name('register.choice');
Route::get('/register/form', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'doRegister'])->name('register.do');

Route::get('/register/trainer', [AuthController::class, 'showRegisterTrainer'])->name('register.trainer.form');
Route::post('/register/trainer', [AuthController::class, 'doRegisterTrainer'])->name('register.trainer.do');

Route::get('/register/client', [ClientRegisterController::class, 'show'])->name('register.client.show');
Route::post('/register/client', [ClientRegisterController::class, 'store'])->name('register.client.store');

// Admin routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth.required', 'role:0'])
    ->group(function () {
        Route::get('/home', [AdminController::class, 'home'])->name('home');
        Route::get('/trainers', [AdminController::class, 'indexTrainers'])->name('trainers.index');
        Route::get('/trainers/create', [AdminController::class, 'createTrainer'])->name('trainers.create');
        Route::post('/trainers', [AdminController::class, 'storeTrainer'])->name('trainers.store');
        Route::get('/trainers/{username}/edit', [AdminController::class, 'editTrainer'])->name('trainers.edit');
        Route::put('/trainers/{username}', [AdminController::class, 'updateTrainer'])->name('trainers.update');
        Route::delete('/trainers/{username}', [AdminController::class, 'destroyTrainer'])->name('trainers.destroy');
    });

// Trainer routes with middleware
Route::prefix('trainer')
    ->name('trainer.')
    ->middleware(['auth.required', 'role:2'])
    ->group(function () {

        Route::resource('foods', FoodController::class);
        Route::resource('programs', ProgramController::class);
        Route::resource('programs.items', ItemsLatihanController::class)->shallow();
    });



// User home routes
Route::get('/user/home', function () {
    return view('user_home');
})->name('user.home')->middleware(['auth.required', 'role:1']);

Route::get('/trainer/home', [TrainerHomeController::class, 'index'])
    ->name('trainer.home')->middleware(['auth.required', 'role:2']);

// Profile trainer routes
Route::prefix('profile/trainer')
    ->middleware(['auth.required', 'role:2'])
    ->group(function () {

    Route::get('/', [ProfilTrainerController::class, 'showTrainer'])
        ->name('profile.trainer.show');

    Route::post('/', [ProfilTrainerController::class, 'updateTrainer'])
        ->name('profile.trainer.update');
});

// Profile client routes
Route::prefix('profile/client')
    ->middleware(['auth.required', 'role:1'])
    ->group(function () {

    Route::get('/', [ProfileController::class, 'show'])
        ->name('profile.client.show');

    Route::post('/', [ProfileController::class, 'update'])
        ->name('profile.client.update');
});




Route::get('/client/home', [HomeController::class, 'index'])
    ->name('client.home')
    ->middleware(['auth.required', 'role:1']);

Route::prefix('client')->name('client.')->middleware(['auth.required', 'role:1'])->group(function () {
    Route::get('/diary', [DiaryController::class, 'index'])->name('diary');
    Route::get('/diary/add/{category}', [DiaryController::class, 'showAddFood'])->name('diary.add');
    Route::post('/diary/store', [DiaryController::class, 'storeFood'])->name('diary.store');
    Route::delete('/diary/remove', [DiaryController::class, 'removeFood'])->name('diary.remove');

    Route::get('/history', [HistoryController::class, 'index'])->name('history');

    Route::get('/statistic', [StatisticController::class, 'index'])->name('statistic');

    Route::get('/exercise', [ExerciseController::class, 'index'])->name('exercise');
    Route::get('/exercise/{id}', [ExerciseController::class, 'show'])->name('exercise.show');
    Route::get('/exercise/{id}/play', [ExerciseController::class, 'play'])->name('exercise.play');
    Route::post('/exercise/{id}/start', [ExerciseController::class, 'start'])->name('exercise.start');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});