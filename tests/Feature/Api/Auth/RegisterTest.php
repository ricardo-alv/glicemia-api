<?php

use App\Models\User;
use Illuminate\Support\Str;

use function Pest\Laravel\postJson;

it('can register a user', function () {
    $data = [
        'name' => 'Teste Usuário',
        'email' => 'teste@usuario.com',
        'password' => 'senha123',
        'password_confirmation' => 'senha123',
    ];

    $response = postJson(route('auth.register'), $data);
    $response->assertStatus(201);
    $this->assertDatabaseHas('users', [
        'email' => 'teste@usuario.com',
    ]);
});

it('requires a valid email to register', function () {
    // Dados inválidos (email inválido)
    $data = [
        'name' => 'Teste Usuário',
        'email' => 'invalid-email',
        'password' => 'senha123',
        'password_confirmation' => 'senha123',
    ];

    $response = postJson(route('auth.register'), $data);

    $response->assertStatus(422)
    ->assertJsonValidationErrors([
        'email' => trans('validation.email', ['attribute' => 'email']),
    ]);
});

it('validaded password', function () {
    // Validando senha
    $data = [
        'name' => 'Teste Usuário',
        'email' => 'teste@usuario.com',
        'password' => '12345',
    ];

    $response = postJson(route('auth.register'), $data);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors('password');
});
