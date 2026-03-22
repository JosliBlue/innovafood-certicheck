<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'id_card' => ['required', 'string', 'max:20', 'unique:clients,id_card'],
            'last_names' => ['required', 'string', 'max:100'],
            'first_names' => ['required', 'string', 'max:100'],
            'birthday' => ['required', 'date'],
            'course_name' => ['required', 'string', 'max:150'],
            'subscription_type' => ['required', 'string', 'max:100'],
            'expires_at' => ['required', 'date', 'after:birthday'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'id_card.required' => 'La cédula es obligatoria.',
            'id_card.unique' => 'Ya existe un cliente con esa cédula.',
            'last_names.required' => 'Los apellidos son obligatorios.',
            'first_names.required' => 'Los nombres son obligatorios.',
            'birthday.required' => 'La fecha de nacimiento es obligatoria.',
            'birthday.date' => 'La fecha de nacimiento no es válida.',
            'course_name.required' => 'El nombre del curso es obligatorio.',
            'subscription_type.required' => 'El tipo de suscripción es obligatorio.',
            'expires_at.required' => 'La fecha de vencimiento es obligatoria.',
            'expires_at.after' => 'La fecha de vencimiento debe ser posterior a la fecha de nacimiento.',
        ];
    }
}
