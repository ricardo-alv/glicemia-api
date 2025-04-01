<?php

namespace Database\Seeders;

use App\Models\GlucoseDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GlucoseDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {     
        GlucoseDay::factory(31)->create();
    }
}
