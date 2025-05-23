<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GlucoseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "description" => $this->description,
            "before_glucose" => $this->before_glucose,
            "ultra_fast_insulin" => $this->ultra_fast_insulin,
            "carbs" => $this->carbs,
            "after_glucose" => $this->after_glucose,
            "glucose_3morning" => $this->glucose_3morning,
            "meal_type" => $this->mealType->name,
            "meal_type_id" =>  $this->mealType->id,
            'created_at' => formatDateBr($this->created_at),
        ];
    }
}
