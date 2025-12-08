<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientRegisterController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemsLatihanController;
use App\Http\Controllers\Client\DiaryController;
use App\Http\Controllers\Client\HistoryController;



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

// Profile routes
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/client/home', function () {
    return view('client_home');
})->name('client.home')->middleware(['auth.required', 'role:1']);

Route::prefix('client')->name('client.')->middleware(['auth.required', 'role:1'])->group(function () {
    Route::get('/diary', [DiaryController::class, 'index'])->name('diary');
    Route::get('/diary/add/{category}', [DiaryController::class, 'showAddFood'])->name('diary.add');
    Route::post('/diary/store', [DiaryController::class, 'storeFood'])->name('diary.store');
    Route::delete('/diary/remove', [DiaryController::class, 'removeFood'])->name('diary.remove');

    Route::get('/history', [HistoryController::class, 'index'])->name('history');
});