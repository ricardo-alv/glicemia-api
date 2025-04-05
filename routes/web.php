<?php

use App\Http\Controllers\Api\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
   return 'Success';
});


Route::get('/reset-password', function () {
    return view('auth.reset_password');
})->name('password.request');

Route::post('/password-update', [ResetPasswordController::class, 'update'])->name('password.update');

