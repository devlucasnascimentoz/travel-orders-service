<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true; // ← Permite a validação
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'E-mail inválido',
            'password.required' => 'A senha é obrigatória'
        ];
    }
}
