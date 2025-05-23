<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    Auth\AuthController,
    Auth\RegisterController,
    Auth\ResetPasswordController,
    Dm1\GlucoseController,
    Dm1\GlucoseDayController,
    Dm1\MealTypeController,
    Dm1\MealController
};

Route::middleware([
    'auth:sanctum',
])->group(function () {

    /**AUTH */
    Route::controller(AuthController::class)
        ->prefix('auth')
        ->name('auth.')
        ->group(function () {
            Route::withoutMiddleware('auth:sanctum')->group(function () {
                Route::post('/register', [RegisterController::class, 'store'])->name('register');
                Route::post('/login', 'login')->name('login');
                Route::post('/send-link-reset-password', [ResetPasswordController::class, 'sendLinkResetPassword'])->name('send.link');
            });

            Route::post('/password-update-user', 'updatePasswordUser')->name('password.update');
            Route::get('/me', 'me')->name('me');
            Route::post('/logout', 'logout')->name('logout');
        });

    /**MealType */
    Route::prefix('v1')
        ->group(function () {
            Route::get('/meals', MealController::class);
        });


    /**Glucose */
    Route::prefix('v1')
        ->group(function () {
            Route::resource('glucose', GlucoseController::class)->except(['create', 'edit', 'show', 'update']);
            Route::get('/glucose/export', [GlucoseController::class], 'export')->name('export');
        });

    /**Glucose Day */
    Route::prefix('v1')
        ->group(function () {
            Route::resource('glucose-days', GlucoseDayController::class)->except(['create', 'edit']);
        });

    /**Glucose */
    Route::prefix('v1')
        ->group(function () {
            Route::resource('glucose', GlucoseController::class)->except(['create', 'edit']);
            Route::get('/glucose/export', [GlucoseController::class, 'export'])->name('export');
        });
});
