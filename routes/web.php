<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboradController;
use Illuminate\Support\Facades\Route;

// Route::middleware('guest')->group(function () {
//     Route::get('/login', [AuthController::class, 'index'])->name('login');
//     Route::get('/signup', [AuthController::class, 'signupPage'])->name('signup');
// });

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/dashboard', [DashboradController::class, 'index'])->name('dashboard');
// });

// The Fallback Route (MUST BE LAST)
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
