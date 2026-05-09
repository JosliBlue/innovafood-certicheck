<?php

namespace App\Http\Requests;

use App\Models\CertificateTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCertificateTemplateFieldsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        foreach (CertificateTemplate::FIELD_KEYS as $key) {
            $rules["fields.{$key}.field_key"] = ['required', Rule::in(CertificateTemplate::FIELD_KEYS)];
            $rules["fields.{$key}.x"] = ['required', 'numeric', 'min:0', 'max:'.CertificateTemplate::DESIGN_WIDTH];
            $rules["fields.{$key}.y"] = ['required', 'numeric', 'min:0', 'max:'.CertificateTemplate::DESIGN_HEIGHT];
            $rules["fields.{$key}.width"] = ['required', 'numeric', 'min:20', 'max:'.CertificateTemplate::DESIGN_WIDTH];
            $rules["fields.{$key}.font_size"] = ['required', 'numeric', 'min:8', 'max:400'];
            $rules["fields.{$key}.font_color"] = ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'];
            $rules["fields.{$key}.font_weight"] = ['required', Rule::in(['normal', 'bold'])];
        }

        return $rules;
    }
}
