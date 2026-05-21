<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    /** Ancho lógico del lienzo (mismo esquema que carnets Ligatactica: × 0.1 ⇒ mm en PDF). */
    public const DESIGN_WIDTH = 2970;

    /** Alto lógico (A4 apaisado 210 mm). */
    public const DESIGN_HEIGHT = 2100;

    public const DESIGN_TO_MM = 0.1;

    /**
     * DomPDF coloca la primera línea por debajo del borde superior del bloque (métricas de fuente).
     */
    public const PDF_Y_CORRECTION_FACTOR = 0.40;

    /** Orden de pintado en el PDF (debajo → arriba visualmente por z-index si hace falta). */
    public const FIELD_KEYS = [
        'full_name',
        'id_card',
        'finished_at',
    ];

    /** @var array<string, string> */
    public const FIELD_LABELS = [
        'full_name' => 'Nombres y apellidos',
        'id_card' => 'Cédula',
        'finished_at' => 'Fecha de finalización',
    ];

    protected $fillable = [
        'name',
        'background_mime',
        'background_base64',
        'background_back_mime',
        'background_back_base64',
        'fields',
    ];

    protected function casts(): array
    {
        return [
            'fields' => 'array',
        ];
    }

    public function backgroundDataUri(): ?string
    {
        $mime = $this->background_mime;
        $data = $this->background_base64;

        if ($mime === null || $mime === '' || $data === null || $data === '') {
            return null;
        }

        return 'data:'.$mime.';base64,'.$data;
    }

    public function backgroundBackDataUri(): ?string
    {
        $mime = $this->background_back_mime;
        $data = $this->background_back_base64;

        if ($mime === null || $mime === '' || $data === null || $data === '') {
            return null;
        }

        return 'data:'.$mime.';base64,'.$data;
    }

    public function hasBackBackground(): bool
    {
        return $this->backgroundBackDataUri() !== null;
    }

    /**
     * Definición por defecto por field_key (coordenadas en espacio de diseño).
     *
     * font_family: clave en config/certificate_fonts.php → families.
     *
     * @return array<string, array{field_key: string, x: float, y: float, width: float, font_size: float, font_color: string, font_weight: string, font_family: string}>
     */
    public static function defaultFieldsKeyed(): array
    {
        return [
            'full_name' => [
                'field_key' => 'full_name',
                'x' => 400,
                'y' => 700,
                'width' => 2170,
                'font_size' => 130,
                'font_color' => '#1a1a1a',
                'font_weight' => 'bold',
                'font_family' => 'dejavu_sans',
            ],
            'id_card' => [
                'field_key' => 'id_card',
                'x' => 400,
                'y' => 920,
                'width' => 2170,
                'font_size' => 85,
                'font_color' => '#333333',
                'font_weight' => 'normal',
                'font_family' => 'dejavu_sans',
            ],
            'finished_at' => [
                'field_key' => 'finished_at',
                'x' => 400,
                'y' => 1100,
                'width' => 2170,
                'font_size' => 75,
                'font_color' => '#333333',
                'font_weight' => 'normal',
                'font_family' => 'dejavu_sans',
            ],
        ];
    }

    /**
     * Combina lo guardado en BD con valores por defecto (campos nuevos o plantillas antiguas).
     *
     * @return array<string, array<string, mixed>>
     */
    public function mergedFieldsKeyed(): array
    {
        $defaults = self::defaultFieldsKeyed();
        $stored = is_array($this->fields) ? $this->fields : [];

        if (! isset($stored['full_name']) && is_array($stored['first_names'] ?? null)) {
            $stored['full_name'] = $stored['first_names'];
        }

        $out = [];
        foreach (self::FIELD_KEYS as $key) {
            $block = isset($stored[$key]) && is_array($stored[$key])
                ? array_merge($defaults[$key], $stored[$key])
                : $defaults[$key];
            $block['field_key'] = $key;
            unset($block['text_align']);
            $out[$key] = $block;
        }

        return $out;
    }

    /**
     * Lista ordenada para el PDF y el editor.
     *
     * @return array<int, array<string, mixed>>
     */
    public function orderedFields(): array
    {
        $keyed = $this->mergedFieldsKeyed();
        $list = [];
        foreach (self::FIELD_KEYS as $key) {
            $list[] = $keyed[$key];
        }

        return $list;
    }

    public static function normalizeCourseName(string $courseName): string
    {
        return mb_strtolower(trim($courseName));
    }

    public static function findForCourseName(string $courseName): ?self
    {
        $normalized = self::normalizeCourseName($courseName);

        if ($normalized === '') {
            return null;
        }

        $templateId = null;

        foreach (self::query()->select(['id', 'name'])->cursor() as $template) {
            if (self::normalizeCourseName($template->name) === $normalized) {
                $templateId = $template->id;
                break;
            }
        }

        if ($templateId === null) {
            return null;
        }

        return self::query()->find($templateId);
    }
}
