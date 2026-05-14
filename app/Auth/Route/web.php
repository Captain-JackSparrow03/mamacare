<?php

use Illuminate\Support\Facades\Route;

use App\auth\Controllers\AuthController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');