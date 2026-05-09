<?php

namespace App\Services;

use App\Models\CertificateTemplate;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf as PdfFacade;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CertificatePdfService
{
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
            'first_names' => $client->first_names,
            'last_names' => $client->last_names,
            'id_card' => 'C.I. '.$client->id_card,
            'course_name' => $client->course_name,
            'academic_hours' => $client->academic_hours.' horas cursadas',
            'finished_at' => $client->finished_at->format('d/m/Y'),
            default => '',
        };

        return [
            'field_key' => $key,
            'value' => $value,
            'x_mm' => $this->designToMm($field['x'] ?? 0),
            'y_mm' => $this->designToMm($field['y'] ?? 0),
            'width_mm' => $this->designToMm($field['width'] ?? 100),
            'font_size_mm' => $this->designToMm($field['font_size'] ?? 12),
            'font_color' => $field['font_color'] ?? '#111111',
            'font_weight' => $field['font_weight'] ?? 'normal',
            'text_align' => 'left',
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildPdfFields(Client $client, CertificateTemplate $template): array
    {
        return collect($template->orderedFields())
            ->map(fn (array $field) => $this->mapFieldForPdf($field, $client))
            ->values()
            ->all();
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
        return [
            'background_data_uri' => $this->backgroundDataUri($template),
            'pdf_fields' => $this->buildPdfFields($client, $template),
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
        $payload = $this->viewPayload($client, $template);

        $pdf = PdfFacade::setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ])->loadView('pdf.certificate', $payload)->setPaper('a4', 'landscape');

        return $pdf->download($this->filename($client, $template));
    }
}
