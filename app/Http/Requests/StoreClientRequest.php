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
            'id_card' => ['required', 'string', 'max:20'],
            'last_names' => ['required', 'string', 'max:150'],
            'first_names' => ['required', 'string', 'max:150'],
            'course_name' => ['required', 'string', 'max:150'],
            'finished_at' => ['required', 'date'],
            'academic_hours' => ['required', 'integer', 'min:10', 'max:100'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'id_card.required' => 'La cédula es obligatoria.',
            'last_names.required' => 'Los apellidos son obligatorios.',
            'first_names.required' => 'Los nombres son obligatorios.',
            'course_name.required' => 'El nombre del curso es obligatorio.',
            'finished_at.required' => 'La fecha de finalización es obligatoria.',
            'finished_at.date' => 'La fecha de finalización no es válida.',
            'academic_hours.required' => 'Las horas académicas son obligatorias.',
            'academic_hours.integer' => 'Las horas académicas deben ser un número.',
        ];
    }
}
