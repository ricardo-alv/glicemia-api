<?php

use App\Mail\GlucoseReportMail;
use App\Models\Glucose;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    // Configurações antes de cada teste, como criar um usuário
    $this->user = User::factory()->create([
        'email' => 'docinhocontrole@gmail.com',
        'password' => Hash::make('password'),
    ]);

    $faker = \Faker\Factory::create();

    Glucose::factory(20)->create([
        'user_id' => $this->user->id,
        'description' => $faker->word,
        'glucose_level' => $faker->numberBetween(50, 300),
        'insulin_amount' => $faker->randomFloat(1, 0.5, 3),
    ]);
});

it('can export glucose data', function () {
    $this->actingAs($this->user);
    $response = getJson(route('glucose.export', [
        'period_start' => now()->format('Y-m-d'),
        'period_final' => now()->format('Y-m-d'),
    ]));
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
});

it('can fetch all glucose records', function () {
    // Realiza uma requisição GET para buscar os registros de glicose
    $this->actingAs($this->user);
    $response = getJson(route('glucose.index'));

    $response->assertStatus(200)
        ->assertJsonCount(15, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'description', 'glucose_level', 'created_by', 'created_at'],
            ],
            'meta' => ['current_page', 'per_page', 'last_page', 'path', 'to', 'from', 'links']
        ]);
});



it('can fetch a specific glucose record', function () {
    $this->actingAs($this->user);
    $glucose = Glucose::factory()->create();

    $response = getJson(route('glucose.show', ['id' => $glucose->id]));

    $response->assertStatus(200)
        ->assertJsonFragment([
            'data' => [
                'id' => $glucose->id,
                'description' => $glucose->description,
                'glucose_level' => $glucose->glucose_level,
                'insulin_amount' => $glucose->insulin_amount,
                'created_by' => $this->user->name,
                'created_at' => formatDateBr($glucose->created_at), // Ajuste para o formato correto
            ]
        ]);
});

it('can create a new glucose record', function () {
    // Dados válidos para criar um novo registro de glicose
    $this->actingAs($this->user);
    $data = [
        "description" => "Glicemia antes de ir para escola",
        "glucose_level" => 50,
        "insulin_amount" => null,
    ];

    $response = postJson(route('glucose.store'), $data);
    $response->assertStatus(201);
    $this->assertDatabaseHas('glucoses', $data);
});

it('can update a glucose record', function () {

    $this->actingAs($this->user);
    $glucose = Glucose::factory()->create();

    // Dados para atualizar o registro
    $data = [
        'description' => 'Cafe da manha - 2 fatias de pao',
        'glucose_level' =>  $glucose->glucose_level,
    ];

    $response = putJson(route('glucose.update', ['id' => $glucose->id]), $data);
    $response->assertStatus(200);
    $this->assertDatabaseHas('glucoses', $data);
});

it('can delete a glucose record', function () {
    // Criar um registro de glicose para deletar
    $this->actingAs($this->user);
    $glucose = Glucose::factory()->create();

    $response = deleteJson(route('glucose.destroy', ['id' => $glucose->id]));
    $response->assertStatus(204);

    $this->assertDatabaseMissing('glucoses', ['id' => $glucose->id]);
});


it('checking if there is a record', function () {
    $this->actingAs($this->user);

    $response = getJson(route('glucose.show', ['id' => 'id_faker']));
    $response->assertStatus(404);
});

it('sends a glucose report email', function () {
    // Finge o envio de e-mails
    Mail::fake();

    $pdfContent = 'PDF_CONTENT'; 
    $textPeriod = "Período de " . formatDateBr(now()) . " até " . formatDateBr(now());
    $email = $this->user->email;

    // Chama o envio de e-mail manualmente
    Mail::send(new GlucoseReportMail($pdfContent, $textPeriod, $email));

    Mail::assertSent(GlucoseReportMail::class, function ($mail) use ($email, $textPeriod) {
        return $mail->hasTo($email) &&
               $mail->getTextPeriod() === $textPeriod;
    });    
});