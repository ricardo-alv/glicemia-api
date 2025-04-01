<?php

namespace App\Http\Requests\Api\GlucoseDay;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdate extends FormRequest
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
        $rules = [
            'description' => 'string|nullable|min:3|max:255',
            'date' => 'required|date|date_format:Y-m-d',
            'basal' => 'string|nullable',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {    
            $uuid = $this->route('glucose_day'); // Pega o ID do glucose_day
            $userId = auth()->user()->id; // Pega o user_id do usuário autenticado
    
            // Validação para garantir que a data seja única por user_id
            $rules['date'] = 'required|date|date_format:Y-m-d|unique:glucose_days,date,' . $uuid . ',id,user_id,' . $userId;
        } else {  
            // Para criação (POST), garantir que a data seja única por user_id
            $userId = auth()->user()->id; // Pega o user_id do usuário autenticado
            $rules['date'] = 'required|date|date_format:Y-m-d|unique:glucose_days,date,NULL,id,user_id,' . $userId;
        }
        
        return $rules;
    }
}
