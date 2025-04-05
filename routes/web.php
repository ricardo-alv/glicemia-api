<?php

use App\Http\Controllers\Api\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
   return 'Success';
});


Route::get('/reset-password', function () {
    return view('auth.reset_password');
})->name('password.request');

Route::post('/password-update', [ResetPasswordController::class, 'update'])->name('password.update');


Route::get('/teste-pdf', function () {
    $data = ['titulo' => 'PDF gerado com sucesso!'];

    $pdf = Pdf::loadView('exemplo', $data);

    return $pdf->stream('teste.pdf');
});

