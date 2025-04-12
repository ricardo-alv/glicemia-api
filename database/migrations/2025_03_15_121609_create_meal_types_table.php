<?php

use App\Models\MealType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar se a tabela já existe
        if (!Schema::hasTable('meal_types')) {
            Schema::create('meal_types', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        // // Inserindo registros fixos
        // $mealTypes = [
        //     ['name' => 'Café da Manhã'],
        //     ['name' => 'Almoço'],
        //     ['name' => 'Colação'],
        //     ['name' => 'Jantar'],
        //     ['name' => 'Ceia'],
        // ];

        // foreach ($mealTypes as $mealType) {
        //     MealType::firstOrCreate([
        //         'name' => $mealType['name']
        //     ]);
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_types');
    }
};
