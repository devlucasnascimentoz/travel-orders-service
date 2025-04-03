<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ajustar caso precise de autorização específica
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:aprovado,cancelado',
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser "aprovado" ou "cancelado".',
        ];
    }
}
