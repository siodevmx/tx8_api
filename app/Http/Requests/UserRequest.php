<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'rol.required' => 'El rol es obligatorio.',
            'name.max' => 'No más de 255 caracateres.',
            'name.string' => 'El nombre debe de ser texto',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser email.',
            'email.unique' => 'Este email ya se ha registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'Minimo 6 caracteres.',
        ];
    }
}
