<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientRegisterController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\ProgramController;
;



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

});


// Role-specific example pages
Route::get('/user/home', function () {
    return view('user_home');
})->name('user.home')->middleware(['auth.required', 'role:1']);

Route::get('/trainer/home', function () {
    return view('trainer_home');
})->name('trainer.home')->middleware(['auth.required', 'role:2']);