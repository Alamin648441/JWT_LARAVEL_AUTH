<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('backend.dashboard.ecommerce_dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
Route::get('/ajax/users', [AjaxController::class, 'index'])->name('user.list');
Route::delete('delete/{id}', [AjaxController::class,'destroy'])->name('user.destroy');

Route::get('/user', [AjaxController::class, 'index']);
Route::post('/user', [AjaxController::class, 'store'])->name('user.store');




require __DIR__.'/auth.php';
