<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Certificado</title>
    <style>
        {!! $font_face_css !!}

        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
        }

        .page {
            width: 297mm;
            height: 210mm;
            position: relative;
            overflow: hidden;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .bg {
            position: absolute;
            inset: 0;
            width: 297mm;
            height: 210mm;
            z-index: 0;
        }

        .bg img {
            width: 100%;
            height: 100%;
            display: block;
        }

        .field {
            position: absolute;
            margin: 0;
            padding: 0;
            line-height: 1;
            height: auto;
            z-index: 1;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            box-sizing: border-box;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="page">
        @if (!empty($background_data_uri))
            <div class="bg">
                <img src="{{ $background_data_uri }}" alt="">
            </div>
        @endif

        @foreach ($pdf_fields as $field)
            <div class="field"
                style="left: {{ $field['x_mm'] }}mm; top: {{ $field['y_mm'] }}mm; width: {{ $field['width_mm'] }}mm; height: {{ $field['line_height_mm'] }}mm; font-size: {{ $field['font_size_mm'] }}mm; line-height: {{ $field['line_height_mm'] }}mm; color: {{ $field['font_color'] }}; font-weight: {{ $field['font_weight'] }}; font-family: '{{ $field['font_css_family'] }}', DejaVu Sans, sans-serif; text-align: {{ $field['text_align'] }};">
                {{ $field['value'] }}
            </div>
        @endforeach
    </div>

    @if (!empty($background_back_data_uri))
        <div class="page">
            <div class="bg">
                <img src="{{ $background_back_data_uri }}" alt="">
            </div>
        </div>
    @endif
</body>

</html>
