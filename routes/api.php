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

use Illuminate\Http\Request;

Route::post('/artisan-command', function (Request $request) {

    // Verifica o token de segurança
    $tokenRecebido = $request->header('X-MIGRATE-TOKEN');
    $tokenEsperado = env('MIGRATE_SECRET');

    $comando = $request->input('command');

    if ($tokenRecebido !== $tokenEsperado) {
        return response()->json(['message' => 'Não autorizado.'], 401);
    }

    // Valida o comando enviado na requisição
    $comando = $request->input('command');

    if (!$comando) {
        return response()->json(['message' => 'Comando não especificado.'], 400);
    }

    try {
        // Executa o comando Artisan com os parâmetros passados
        Artisan::call($comando, ['--force' => true]);

        return response()->json([
            'message' => "{$comando} executado com sucesso.",
            'output' => Artisan::output()
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Erro ao executar o comando.',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'output' => Artisan::output(),
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

            Route::post('/password-update-user', 'updatePasswordUser')->name('password.update');
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
