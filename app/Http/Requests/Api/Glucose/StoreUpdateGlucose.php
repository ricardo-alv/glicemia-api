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
            'glucose_days_id' => 'required',
            'id' => 'string|nullable',
            'meal_type_id' => 'required|string',
            'before_glucose' => 'string|nullable',
            'ultra_fast_insulin' => 'string|nullable',
            'carbs' => 'string|nullable',
            'after_glucose' => 'string|nullable',
            'glucose_3morning' => 'string|nullable',
        ];
    }
}
