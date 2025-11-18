<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;

Route::controller(RegisterController::class)->group(function () {
    Route::post('/user-register', 'userRegister')->name('api.user-register');
    Route::post('/resend-otp', 'otpResend')->name('api.resend-otp');
    Route::post('/verify-otp', 'otpVerify')->name('api.verify-otp');
});

Route::controller(LoginController::class)->group(function () {
    Route::post('/user-login', 'userLogin')->name('api.user-login');
    Route::post('/email-verify', 'emailVerify')->name('api.email-verify');
    Route::post('/login/resend-otp', 'otpResend')->name('api.login-resend-otp');
    Route::post('/login/verify-otp', 'otpVerify')->name('api.login-verify-otp');
    Route::post('/forgot-password','forgotPassword')->name('forgot.password');
    Route::post('/reset-password','resetPass')->name('reset.password');
});

Route::controller(LoginController::class)->group(function () {
    Route::post('/pass-change','passChange')->name('change.password');


})->middleware('jwt.verify');
