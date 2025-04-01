<?php

namespace Database\Factories;

use App\Models\GlucoseDay;
use App\Models\GlucoseDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GlucoseDetail>
 */
class GlucoseDayFactory extends Factory
{

    protected $model = GlucoseDay::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [           
            'user_id' => User::first(), // Gerar um usuário aleatório
            'description' => $this->faker->sentence(),
            'date' => null,
            'basal' => $this->faker->randomFloat(2, 0, 10),
        ];
    }

    public function configure()
    {
        return $this->sequence(fn ($seq) => [
            'date' => Carbon::now()->startOfMonth()->addDays($seq->index),
        ]);
    }
}
