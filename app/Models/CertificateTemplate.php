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

    /** Orden de pintado en el PDF (debajo → arriba visualmente por z-index si hace falta). */
    public const FIELD_KEYS = [
        'first_names',
        'last_names',
        'id_card',
        'course_name',
        'academic_hours',
        'finished_at',
    ];

    /** @var array<string, string> */
    public const FIELD_LABELS = [
        'first_names' => 'Nombres',
        'last_names' => 'Apellidos',
        'id_card' => 'Cédula',
        'course_name' => 'Nombre del curso',
        'academic_hours' => 'Horas cursadas',
        'finished_at' => 'Fecha finalización',
    ];

    protected $fillable = [
        'name',
        'background_mime',
        'background_base64',
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
            'first_names' => [
                'field_key' => 'first_names',
                'x' => 400,
                'y' => 620,
                'width' => 2170,
                'font_size' => 130,
                'font_color' => '#1a1a1a',
                'font_weight' => 'bold',
                'font_family' => 'dejavu_sans',
            ],
            'last_names' => [
                'field_key' => 'last_names',
                'x' => 400,
                'y' => 800,
                'width' => 2170,
                'font_size' => 130,
                'font_color' => '#1a1a1a',
                'font_weight' => 'bold',
                'font_family' => 'dejavu_sans',
            ],
            'id_card' => [
                'field_key' => 'id_card',
                'x' => 400,
                'y' => 980,
                'width' => 2170,
                'font_size' => 85,
                'font_color' => '#333333',
                'font_weight' => 'normal',
                'font_family' => 'dejavu_sans',
            ],
            'course_name' => [
                'field_key' => 'course_name',
                'x' => 320,
                'y' => 1160,
                'width' => 2330,
                'font_size' => 95,
                'font_color' => '#1a1a1a',
                'font_weight' => 'normal',
                'font_family' => 'dejavu_sans',
            ],
            'academic_hours' => [
                'field_key' => 'academic_hours',
                'x' => 400,
                'y' => 1340,
                'width' => 2170,
                'font_size' => 75,
                'font_color' => '#333333',
                'font_weight' => 'normal',
                'font_family' => 'dejavu_sans',
            ],
            'finished_at' => [
                'field_key' => 'finished_at',
                'x' => 400,
                'y' => 1510,
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
}
