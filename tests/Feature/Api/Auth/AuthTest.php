<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\{getJson, postJson};


beforeEach(function () {
    // Configurações antes de cada teste, como criar um usuário
    $this->user = User::factory()->create([
        'email' => 'docinhocontrole@gmail.com',
        'password' => Hash::make('password'),
    ]);
});

it('can login with valid credentials', function () {
    // Fazendo o login com as credenciais do usuário
    $response = postJson(route('auth.login'), [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    // Verificando se a resposta contém um token
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'token',
            ]
        ]);
});

it('cannot login with invalid credentials', function () {
    // Tentando fazer login com credenciais inválidas
    $response = postJson(route('auth.login'), [
        'email' => 'fake@mail.com',
        'password' => '12345678',
    ]);

    // Esperando o status 404 (credentials not found )
    $response->assertStatus(404);
});

it('validaded password', function () {
    // Tentando fazer login com credenciais inválidas
    $response = postJson(route('auth.login'), [
        'email' => $this->user->email,
        'password' => '12345',
    ]);
    

    $response->assertStatus(422)
        ->assertJsonValidationErrors('password');
});


it('can access user details when authenticated', function () {
    $this->actingAs($this->user);

    $response = getJson(route('auth.me'));

    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->where('data.user.email', 'docinhocontrole@gmail.com'));
});

it('can logout successfully', function () {
    $this->actingAs($this->user);
    $response = postJson(route('auth.logout'), []);
    // Verificando se o logout foi bem-sucedido
    $response->assertStatus(204);
});
