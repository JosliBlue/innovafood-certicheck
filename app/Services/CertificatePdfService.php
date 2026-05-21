<?php

namespace App\Services;

use App\Models\CertificateTemplate;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf as PdfFacade;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CertificatePdfService
{
    private function ensureDompdfFontDirectoryExists(): void
    {
        $fontDir = storage_path('fonts');

        if (! is_dir($fontDir)) {
            mkdir($fontDir, 0755, true);
        }
    }

    /**
     * Incrusta TTF como data URI para DomPDF (evita HTTP remoto con isRemoteEnabled).
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
                $fullPath = public_path($path);
                if (! is_readable($fullPath)) {
                    continue;
                }
                $binary = @file_get_contents($fullPath);
                if ($binary === false || $binary === '') {
                    continue;
                }
                $dataUri = 'data:font/ttf;base64,'.base64_encode($binary);
                $weight = $face['weight'] ?? 'normal';
                $style = $face['style'] ?? 'normal';
                $blocks[] = "@font-face{font-family:'{$family}';src:url('{$dataUri}') format('truetype');font-weight:{$weight};font-style:{$style};}";
            }
        }

        return implode("\n", $blocks);
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
            'text_align' => 'left',
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
            'background_data_uri' => $this->backgroundDataUri($template),
            'background_back_data_uri' => $template->backgroundBackDataUri(),
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
        $this->ensureDompdfFontDirectoryExists();

        $payload = $this->viewPayload($client, $template);

        $pdf = PdfFacade::setOptions([
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true,
        ], mergeWithDefaults: true)
            ->loadView('pdf.certificate', $payload)
            ->setPaper('a4', 'landscape');

        return $pdf->download($this->filename($client, $template));
    }
}
