<?php

namespace App\Http\Requests\Api\Glucose;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateGlucose extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'glucose_days_id' => 'required|uuid|exists:glucose_days,id',
            'meal_type_id' => 'required|string|exists:meal_types,id',
            'description' => 'nullable|string|min:3|max:255',
            'before_glucose' => 'nullable|string',
            'ultra_fast_insulin' => 'nullable|string',
            'carbs' => 'nullable|string',
            'after_glucose' => 'nullable|string',
            'glucose_3morning' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'meal_type_id' => 'refeição',
            'glucose_days_id' => 'dia da glicemia',
            'ultra_fast_insulin' => 'ultra rápida',
            'before_glucose' => 'glicemia antes',
            'carbs' => 'CHO',
            'after_glucose' => 'glicemia após 2h',
            'glucose_3morning' => 'glicemia às 3h da manhã',
        ];
    }
}
