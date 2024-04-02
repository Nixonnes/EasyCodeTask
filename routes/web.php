<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::post('/confirm/telegram', [\App\Http\Controllers\ConfirmationController::class, 'TelegramConfirm']);
Route::post('/confirm/sms', [\App\Http\Controllers\ConfirmationController::class, 'SMSConfirm']);
Route::post('/confirm/email', [\App\Http\Controllers\ConfirmationController::class, 'EmailConfirm']);
Route::post('/update-name', [UserController::class, 'updateName']);
require __DIR__.'/auth.php';
