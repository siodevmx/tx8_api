<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'surnames' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'phone' => 'required|numeric|digits:10|unique:users,phone',
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
            'name.max' => 'No más de 255 caracateres.',
            'name.string' => 'El nombre debe de ser texto',
            'surnames.max' => 'No más de 255 caracateres.',
            'surnames.string' => 'Los apellidos deben de ser texto',
            'surnames.required' => 'Los apellidos son obligatorios',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser email.',
            'email.unique' => 'Este email ya se ha registrado.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.digits' => 'El teléfono debe tener 10 caracteres.',
            'phone.numeric' => 'El teléfono debe ser numérico.',
            'phone.unique' => 'Este teléfono ya se ha registrado.'
        ];
    }
}
