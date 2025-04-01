<?php

namespace Database\Factories;

use App\Models\Glucose;
use App\Models\GlucoseDay;
use App\Models\GlucoseDetail;
use App\Models\MealType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Glucose>
 */
class GlucoseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Glucose::class;

    public function definition(): array
    {
          return [
            'user_id' => User::first(), // Gerar um usuário aleatório
            'meal_type_id' => MealType::inRandomOrder()->first()->id, 
            'glucose_days_id' => GlucoseDay::inRandomOrder()->first()->id,
            'before_glucose' => $this->faker->numberBetween(50, 300), // Nível de glicemia antes
            'ultra_fast_insulin' => $this->faker->randomFloat(2, 0, 5), // Quantidade de insulina rápida
            'carbs' => $this->faker->randomFloat(2, 0, 100), // Quantidade de carboidratos
            'after_glucose' => $this->faker->numberBetween(50, 300), // 
            'glucose_3morning' => $this->faker->numberBetween(50, 300), // 
        ];
    }
}
