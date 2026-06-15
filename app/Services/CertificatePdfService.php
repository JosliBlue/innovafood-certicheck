<?php

namespace App\Services;

use App\Models\CertificateTemplate;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf as PdfFacade;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CertificatePdfService
{
    private function dompdfFontDirectory(): string
    {
        foreach ([storage_path('fonts'), sys_get_temp_dir().'/innovafood-dompdf-fonts'] as $fontDir) {
            if (! is_dir($fontDir)) {
                mkdir($fontDir, 0755, true);
            }

            if (is_dir($fontDir) && is_writable($fontDir)) {
                return $fontDir;
            }
        }

        throw new \RuntimeException('No hay un directorio escribible para las fuentes del PDF.');
    }

    private function dompdfTempDirectory(): string
    {
        $tempDir = storage_path('app/dompdf-tmp');

        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        if (is_dir($tempDir) && is_writable($tempDir)) {
            return $tempDir;
        }

        return sys_get_temp_dir();
    }

    /**
     * @return array<string, mixed>
     */
    private function dompdfOptions(): array
    {
        $fontDir = $this->dompdfFontDirectory();

        return array_merge(config('dompdf.options', []), [
            'font_dir' => $fontDir,
            'font_cache' => $fontDir,
            'temp_dir' => $this->dompdfTempDirectory(),
            'chroot' => realpath(base_path()) ?: base_path(),
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true,
        ]);
    }

    /**
     * Referencia TTF locales (file://) para DomPDF; evita data URI enormes y escritura en vendor/.
     *
     * @param  array<int, string>  $fontFamilyKeys
     */
    private function buildFontFaceCss(array $fontFamilyKeys): string
    {
        /** @var array<string, array{label: string, css_family: string, faces: array<int, array{weight: string, style: string, path: string}>}> $families */
        $families = config('certificate_fonts.families', []);

        $blocks = [];
        foreach (array_unique($fontFamilyKeys) as $fontKey) {
            $def = $families[$fontKey] ?? null;
            if ($def === null || ($def['faces'] ?? []) === []) {
                continue;
            }
            $family = $def['css_family'];
            foreach ($def['faces'] as $face) {
                $path = $face['path'] ?? '';
                if ($path === '') {
                    continue;
                }
                $fullPath = realpath(public_path($path));
                if ($fullPath === false || ! is_readable($fullPath)) {
                    continue;
                }
                $weight = $face['weight'] ?? 'normal';
                $style = $face['style'] ?? 'normal';
                $blocks[] = "@font-face{font-family:'{$family}';src:url('file://{$fullPath}') format('truetype');font-weight:{$weight};font-style:{$style};}";
            }
        }

        return implode("\n", $blocks);
    }

    private function optimizeImageDataUri(?string $dataUri, int $maxWidthPx = 2480): ?string
    {
        if ($dataUri === null || $dataUri === '' || ! extension_loaded('gd')) {
            return $dataUri;
        }

        if (! preg_match('#^data:(image/[a-zA-Z0-9.+-]+);base64,(.+)$#', $dataUri, $matches)) {
            return $dataUri;
        }

        $binary = base64_decode($matches[2], true);
        if ($binary === false || $binary === '') {
            return $dataUri;
        }

        $image = @imagecreatefromstring($binary);
        if ($image === false) {
            return $dataUri;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxWidthPx) {
            imagedestroy($image);

            return $dataUri;
        }

        $newHeight = (int) round($height * ($maxWidthPx / $width));
        $resized = imagescale($image, $maxWidthPx, $newHeight);
        imagedestroy($image);

        if ($resized === false) {
            return $dataUri;
        }

        ob_start();
        imagejpeg($resized, null, 85);
        imagedestroy($resized);
        $jpeg = ob_get_clean();

        if ($jpeg === false || $jpeg === '') {
            return $dataUri;
        }

        return 'data:image/jpeg;base64,'.base64_encode($jpeg);
    }

    /**
     * @return array{css_family: string}
     */
    private function resolveFontDefinition(string $fontFamilyKey): array
    {
        /** @var array<string, array{label?: string, css_family?: string, faces?: array<int, mixed>}> $families */
        $families = config('certificate_fonts.families', []);
        $def = $families[$fontFamilyKey] ?? $families['dejavu_sans'] ?? ['css_family' => 'DejaVu Sans'];

        return ['css_family' => $def['css_family'] ?? 'DejaVu Sans'];
    }

    private function designToMm(mixed $value): float
    {
        if ($value === null || ! is_numeric($value)) {
            return 0.0;
        }

        return round(((float) $value) * CertificateTemplate::DESIGN_TO_MM, 3);
    }

    /**
     * @param  array<string, mixed>  $field
     * @return array<string, mixed>
     */
    private function mapFieldForPdf(array $field, Client $client): array
    {
        $key = $field['field_key'] ?? '';

        $value = match ($key) {
            'full_name' => trim($client->full_name),
            'id_card' => $client->id_card,
            'finished_at' => $client->finished_at->format('d/m/Y'),
            'started_at' => $client->finished_at
                ? (function () use ($client) {
                    $seed = $client->id ?? crc32($client->id_card ?? 'default');
                    $daysBefore = 30 + (abs((int) $seed) % 3);

                    return $client->finished_at->copy()->subDays($daysBefore)->format('d/m/Y');
                })()
                : '',
            default => '',
        };

        $fontFamilyKey = is_string($field['font_family'] ?? null) ? $field['font_family'] : 'dejavu_sans';
        $fontDef = $this->resolveFontDefinition($fontFamilyKey);

        $fontSizeDesign = is_numeric($field['font_size'] ?? null) ? (float) $field['font_size'] : 12.0;
        $yDesign = is_numeric($field['y'] ?? null) ? (float) $field['y'] : 0.0;
        $yCorrection = ($fontSizeDesign * CertificateTemplate::PDF_Y_CORRECTION_FACTOR) + 12.0;
        $yDesign = max(0.0, $yDesign - $yCorrection);
        $fontSizeMm = $this->designToMm($fontSizeDesign);

        return [
            'field_key' => $key,
            'value' => $value,
            'x_mm' => $this->designToMm($field['x'] ?? 0),
            'y_mm' => $this->designToMm($yDesign),
            'width_mm' => $this->designToMm($field['width'] ?? 100),
            'font_size_mm' => $fontSizeMm,
            'line_height_mm' => $fontSizeMm,
            'font_color' => $field['font_color'] ?? '#111111',
            'font_weight' => $field['font_weight'] ?? 'normal',
            'font_css_family' => $fontDef['css_family'],
            'text_align' => 'center',
        ];
    }

    private function backgroundDataUri(CertificateTemplate $template): ?string
    {
        return $template->backgroundDataUri();
    }

    /**
     * @return array<string, mixed>
     */
    private function viewPayload(Client $client, CertificateTemplate $template): array
    {
        $ordered = $template->orderedFields();
        $fontKeys = [];
        foreach ($ordered as $row) {
            $fontKeys[] = is_string($row['font_family'] ?? null) ? $row['font_family'] : 'dejavu_sans';
        }

        return [
            'background_data_uri' => $this->optimizeImageDataUri($this->backgroundDataUri($template)),
            'background_back_data_uri' => $this->optimizeImageDataUri($template->backgroundBackDataUri()),
            'pdf_fields' => collect($ordered)
                ->map(fn (array $field) => $this->mapFieldForPdf($field, $client))
                ->values()
                ->all(),
            'font_face_css' => $this->buildFontFaceCss($fontKeys),
        ];
    }

    public function filename(Client $client, CertificateTemplate $template): string
    {
        $slugTemplate = Str::slug($template->name);
        $slugPerson = Str::slug($client->full_name);

        return "certificado-{$slugTemplate}-{$slugPerson}-{$client->id_card}.pdf";
    }

    public function download(Client $client, CertificateTemplate $template): Response
    {
        if (! extension_loaded('gd')) {
            throw new \RuntimeException('La extensión PHP GD es necesaria para generar certificados PDF.');
        }

        @ini_set('memory_limit', '512M');

        $payload = $this->viewPayload($client, $template);

        $pdf = PdfFacade::setOptions($this->dompdfOptions())
            ->loadView('pdf.certificate', $payload)
            ->setPaper('a4', 'landscape');

        return $pdf->download($this->filename($client, $template));
    }
}
