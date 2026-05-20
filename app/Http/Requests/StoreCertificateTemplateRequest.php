<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class StoreCertificateTemplateRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            /** max en kilobytes (2 MB, upload_max_filesize por defecto en PHP). */
            'background' => ['required', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'background_back' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'background.uploaded' => $this->backgroundTooLargeHint(),
            'background.max' => 'La imagen no puede superar 2 MB (límite por defecto de PHP).',
            'background_back.max' => 'La imagen no puede superar 2 MB (límite por defecto de PHP).',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'background' => 'imagen de la página 1',
            'background_back' => 'imagen de la página 2',
        ];
    }

    protected function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $file = $this->file('background');
            if (! $file instanceof UploadedFile || $file->isValid()) {
                return;
            }

            $errors = $validator->errors();
            $errors->forget('background');
            $errors->add('background', $this->phpUploadErrorMessage($file->getError()));
        });
    }

    private function phpUploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE => 'La imagen supera el límite de 2 MB (upload_max_filesize por defecto en PHP). Comprime el archivo e inténtalo de nuevo.',
            UPLOAD_ERR_FORM_SIZE => 'La imagen supera el límite MAX_FILE_SIZE del formulario.',
            UPLOAD_ERR_PARTIAL => 'La subida quedó incompleta; vuelve a intentar.',
            UPLOAD_ERR_NO_FILE => 'No se recibió ningún archivo.',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta carpeta temporal en el servidor (PHP).',
            UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo en disco en el servidor.',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP bloqueó la subida.',
            default => $this->backgroundTooLargeHint(),
        };
    }

    private function backgroundTooLargeHint(): string
    {
        return 'No se pudo subir la imagen. El tamaño máximo es 2 MB (límite por defecto de PHP).';
    }
}
