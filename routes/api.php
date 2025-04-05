<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    Auth\AuthController,
    Auth\RegisterController,
    Auth\ResetPasswordController,
    Dm1\GlucoseController,
    Dm1\GlucoseDayController,
    Dm1\MealTypeController,
};
use Illuminate\Support\Facades\Artisan;


Route::post('/migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);

        return response()->json([
            'message' => 'Migrations executadas com sucesso.',
            'output' => Artisan::output()
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Erro ao executar migrations.',
            'error' => $e->getMessage()
        ], 500);
    }
});


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

            Route::get('/me', 'me')->name('me');
            Route::post('/logout', 'logout')->name('logout');
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
            Route::resource('glucose', GlucoseController::class)->except(['create', 'edit', 'show', 'update']);        
            Route::get('/glucose/export', [GlucoseController::class, 'export'])->name('export');
        });


    // Route::controller(GlucoseController::class)
    //     ->prefix('v1')
    //     ->name('glucose.')
    //     ->group(function () {
    //         Route::get('/glucose/export', 'export')->name('export');
    //         Route::get('/glucose', 'index')->name('index');
    //         Route::get('/glucose/{id}', 'show')->name('show');
    //         Route::post('/glucose',  'store')->name('store');
    //         Route::put('/glucose/{id}', 'update')->name('update');
    //         Route::delete('/glucose/{id}', 'destroy')->name('destroy');
    //     });
});
