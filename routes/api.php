<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\SoftDeleteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

/// ---------------- Register Routes ----------------
Route::controller(RegisterController::class)->group(function () {
    Route::post('/user-register', 'userRegister')->name('api.user.register');
    Route::post('/resend-otp', 'otpResend')->name('api.user.resend-otp');
    Route::post('/verify-otp', 'otpVerify')->name('api.user.verify-otp');
});

/// ---------------- Login Routes ----------------
Route::controller(LoginController::class)->group(function () {
    Route::post('/user-login', 'userLogin')->name('api.user.login');
    Route::post('/email-verify', 'emailVerify')->name('api.user.email-verify');
    Route::post('/login/resend-otp', 'otpResend')->name('api.user.login-resend-otp');
    Route::post('/login/verify-otp', 'otpVerify')->name('api.user.login-verify-otp');
    Route::post('/forgot-password', 'forgotPassword')->name('api.user.forgot-password');
    Route::post('/reset-password', 'resetPass')->name('api.user.reset-password');
});

/// ---------------- Authenticated Routes ----------------
Route::controller(LoginController::class)->middleware('jwt.verify')->group(function () {
    Route::post('/pass-change', 'passChange')->name('api.user.change-password');
});

/// ---------------- Soft Delete Routes ----------------
Route::controller(SoftDeleteController::class)->group(function () {
    Route::delete('/delete/{id}', 'UserDelete')->name('user.delete');
    Route::get('/withTrashedData', 'withTrashedData')->name('user.withTrashed');
    Route::get('/onlyTrashed', 'onlyTrashed')->name('user.onlyTrashed');
    Route::post('/restore/{id}', 'restore')->name('user.restore');
    Route::get('/home', 'index')->name('user.index');
});
