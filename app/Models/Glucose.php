<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Tenant\Traits\TenantTrait;
use Carbon\Carbon;

class Glucose extends Model
{
    use HasFactory, HasUuids, TenantTrait;

    protected $fillable = ['user_id', 'meal_type_id', 'glucose_days_id', 'description', 'before_glucose', 'ultra_fast_insulin', 'carbs', 'after_glucose', 'glucose_3morning'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mealType()
    {
        return $this->belongsTo(MealType::class);
    }

    public function glucose_days()
    {
        return $this->belongsTo(GlucoseDay::class);
    }
}
