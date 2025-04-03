<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTravelOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ajuste conforme necessidade de permissões
    }

    public function rules(): array
    {
        return [
            'requester_name' => 'required|string|filled|max:255|regex:/^[\pL\s\-]+$/u',
            'destination' => 'required|string|filled|max:255|regex:/^[\pL\s\-]+$/u',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'requester_name.required' => 'O nome do solicitante é obrigatório.',
            'requester_name.regex' => 'O nome do solicitante deve conter apenas letras, espaços e hífens.',
            'destination.required' => 'O destino é obrigatório.',
            'destination.regex' => 'O destino deve conter apenas letras, espaços e hífens.',
            'start_date.required' => 'A data de início da viagem é obrigatória.',
            'start_date.date' => 'A data de início deve ser válida.',
            'start_date.after_or_equal' => 'A data de início deve ser hoje ou uma data futura.',
            'end_date.required' => 'A data de término da viagem é obrigatória.',
            'end_date.date' => 'A data de término deve ser válida.',
            'end_date.after_or_equal' => 'A data de término deve ser igual ou posterior à data de início.',
        ];
    }
}
