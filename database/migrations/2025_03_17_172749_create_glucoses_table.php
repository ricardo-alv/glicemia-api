<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('glucoses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');  // Adiciona a chave estrangeira
            $table->uuid('meal_type_id');
            $table->uuid('glucose_days_id');
            $table->string('description')->nullable();
            $table->integer('before_glucose')->nullable();
            $table->decimal('ultra_fast_insulin', 5, 2)->nullable();
            $table->decimal('carbs', 5, 2)->nullable();
            $table->integer('after_glucose')->nullable();
            $table->integer('glucose_3morning')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('meal_type_id')->references('id')->on('meal_types')->onDelete('cascade');
            $table->foreign('glucose_days_id')->references('id')->on('glucose_days')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glucoses');
    }
};
