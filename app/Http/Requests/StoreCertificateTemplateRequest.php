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
            /** max en kilobytes (~30 MB); debe ser menor que upload_max_filesize / post_max_size en php.ini */
            'background' => ['required', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:30720'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'background.uploaded' => $this->backgroundTooLargeHint(),
            'background.max' => 'La imagen no puede superar :max kilobytes (~30 MB configurados en la app). Comprime el archivo o aumenta upload_max_filesize y post_max_size en PHP.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'background' => 'imagen de fondo',
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
            UPLOAD_ERR_INI_SIZE => 'La imagen supera el límite upload_max_filesize de PHP (ahora suele ser 2M por defecto). Sube una imagen más liviana o aumenta upload_max_filesize y post_max_size en php.ini; con composer puedes usar: composer dev (ya eleva límites al usar artisan serve).',
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
        return 'No se pudo subir la imagen. Suele deberse a que supera upload_max_filesize de PHP (por defecto 2M). Usa composer dev para desarrollo o edita php.ini (upload_max_filesize y post_max_size). Si usas Nginx, revisa también client_max_body_size.';
    }
}
