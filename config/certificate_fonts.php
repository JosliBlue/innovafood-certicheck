<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Fuentes para plantillas de certificado (archivos bajo public/).
    |--------------------------------------------------------------------------
    |
    | Cada clave es el valor guardado en fields.*.font_family. Los TTF se leen
    | desde public/ y se incrustan en el CSS como data URI (sin HTTP).
    |
    */
    'families' => [
        'dejavu_sans' => [
            'label' => 'DejaVu Sans (predeterminado del PDF)',
            'css_family' => 'DejaVu Sans',
            'faces' => [],
        ],
        'inter' => [
            'label' => 'Inter',
            'css_family' => 'Certificate Inter',
            'faces' => [
                ['weight' => 'normal', 'style' => 'normal', 'path' => 'fonts/Imperial_Script,Inter,Pinyon_Script/Inter/static/Inter_24pt-Regular.ttf'],
                ['weight' => 'bold', 'style' => 'normal', 'path' => 'fonts/Imperial_Script,Inter,Pinyon_Script/Inter/static/Inter_24pt-Bold.ttf'],
            ],
        ],
        'imperial_script' => [
            'label' => 'Imperial Script',
            'css_family' => 'Certificate Imperial Script',
            'faces' => [
                ['weight' => 'normal', 'style' => 'normal', 'path' => 'fonts/ImperialScript-Regular.ttf'],
                ['weight' => 'bold', 'style' => 'normal', 'path' => 'fonts/ImperialScript-Regular.ttf'],
            ],
        ],
        'pinyon_script' => [
            'label' => 'Pinyon Script',
            'css_family' => 'Certificate Pinyon Script',
            'faces' => [
                ['weight' => 'normal', 'style' => 'normal', 'path' => 'fonts/PinyonScript-Regular.ttf'],
                ['weight' => 'bold', 'style' => 'normal', 'path' => 'fonts/PinyonScript-Regular.ttf'],
            ],
        ],
    ],
];
