@extends('appsita')

@php
    use App\Models\CertificateTemplate;
    $DW = CertificateTemplate::DESIGN_WIDTH;
    $DH = CertificateTemplate::DESIGN_HEIGHT;
@endphp

@section('title', 'Editor plantilla — '.$template->name)

@section('content')
    <div class="min-h-screen bg-[#faf8f7] flex flex-col font-sans pb-16">

        <header class="relative bg-primary-dark pt-8 pb-16 px-6 border-b border-white/10">
            <div class="max-w-6xl mx-auto flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('certificate-templates.index') }}"
                        class="bg-white/10 hover:bg-white/20 text-white p-2 rounded-xl border border-white/10 shrink-0"
                        title="Volver">
                        <span class="iconify text-xl w-7 h-7" data-icon="line-md:arrow-left"></span>
                    </a>
                    <div>
                        <p class="text-white/50 text-[10px] font-black uppercase tracking-widest">Editor visual</p>
                        <h1 class="text-white text-xl font-black tracking-tight mt-0.5">{{ $template->name }}</h1>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="submit" form="certificate-editor-form"
                        class="bg-white text-primary hover:bg-primary-light font-black py-2.5 px-6 rounded-2xl text-xs shadow-lg transition-colors inline-flex items-center gap-2">
                        <span class="iconify" data-icon="line-md:confirm"></span> Guardar diseño
                    </button>
                </div>
            </div>
            <p class="max-w-6xl mx-auto mt-4 text-white/55 text-[11px] font-semibold leading-relaxed">
                Misma grilla que los carnets Ligatactica: el lienzo mide <strong class="text-white/80">{{ $DW }}×{{ $DH }}</strong>
                unidades de diseño; en PDF cada unidad equivale a <strong class="text-white/80">{{ \App\Models\CertificateTemplate::DESIGN_TO_MM }} mm</strong>.
                Arrastra cada etiqueta sobre la <strong class="text-white/80">página 1</strong> (nombres y apellidos, cédula, fecha de finalización); en el panel puedes subir la <strong class="text-white/80">página 2</strong> para un PDF de dos hojas.
            </p>
        </header>

        <main class="max-w-6xl w-full mx-auto px-6 -mt-8 z-10 space-y-4">

            @if (session('success'))
                <div class="bg-green-50 border border-green-100 text-green-800 text-xs font-bold px-4 py-3 rounded-2xl">
                    {{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-800 text-xs font-bold px-4 py-3 rounded-2xl">
                    Revisa los datos del formulario.
                    <ul class="mt-2 list-disc list-inside font-semibold">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="certificate-editor-form" method="POST" action="{{ route('certificate-templates.update', $template) }}"
                enctype="multipart/form-data"
                class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_340px] gap-6 items-start">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-4 sm:p-6 overflow-hidden space-y-3">
                    <div class="flex gap-2" role="tablist" aria-label="Cara del certificado">
                        <button type="button" id="cert-side-front" data-cert-side="front"
                            class="cert-side-tab flex-1 py-2.5 px-4 rounded-xl text-xs font-black border-2 border-primary bg-primary/10 text-primary transition-colors">
                            Página 1
                        </button>
                        <button type="button" id="cert-side-back" data-cert-side="back"
                            class="cert-side-tab flex-1 py-2.5 px-4 rounded-xl text-xs font-black border-2 border-gray-200 text-gray-500 hover:border-gray-300 transition-colors">
                            Página 2
                        </button>
                    </div>
                    <div id="cert-canvas" class="rounded-2xl border border-gray-200 bg-gray-100 overflow-hidden select-none">
                        <div id="cert-inner"
                            class="relative w-full max-h-[70vh] mx-auto bg-neutral-800"
                            style="aspect-ratio: {{ $DW }} / {{ $DH }}; container-type: inline-size;">
                            <img id="cert-bg-front" src="{{ $bgUrl }}" alt="Página 1"
                                @class([
                                    'absolute inset-0 w-full h-full object-cover pointer-events-none',
                                    'hidden' => $bgUrl === '',
                                ])>
                            <img id="cert-bg-back" src="{{ $bgBackUrl }}" alt="Página 2"
                                class="absolute inset-0 w-full h-full object-cover pointer-events-none hidden">
                            <div id="cert-bg-front-empty"
                                @class([
                                    'absolute inset-0 flex items-center justify-center bg-neutral-700 text-white/70 text-xs font-bold px-4 text-center pointer-events-none',
                                    'hidden' => $bgUrl !== '',
                                ])>
                                Sin imagen de página 1 en BD
                            </div>
                            <div id="cert-bg-back-empty"
                                @class([
                                    'absolute inset-0 flex items-center justify-center bg-neutral-700 text-white/70 text-xs font-bold px-4 text-center pointer-events-none',
                                    'hidden' => $bgBackUrl !== '',
                                ])>
                                Sin imagen de página 2. Súbela en el panel →
                            </div>
                            @foreach ($editorRows as $row)
                                @php
                                    $k = $row['field_key'];
                                @endphp
                                <div role="button" tabindex="0" data-cert-field="{{ $k }}"
                                    class="cert-field absolute z-10 box-border m-0 p-0 text-white shadow-lg cursor-grab active:cursor-grabbing text-left overflow-hidden hover:bg-black/65 transition-colors">
                                    <span class="pointer-events-none block truncate leading-none">{{ $row['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <aside class="bg-white rounded-3xl border border-gray-100 shadow-xl p-6 space-y-5 lg:sticky lg:top-6">
                    <div>
                        <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-widest mb-1.5">Nombre del curso</label>
                        <input type="text" name="name" value="{{ old('name', $template->name) }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-gray-100 text-xs font-bold focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary/40">
                        <p class="text-[10px] text-gray-400 mt-1">Debe coincidir con el curso del cliente para imprimir su certificado.</p>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-gray-50/80 p-4 space-y-4">
                        @if ($template->hasBackBackground())
                            <div class="flex items-start gap-2 rounded-xl bg-emerald-50 border border-emerald-100 px-3 py-2.5">
                                <span class="iconify text-lg text-emerald-600 shrink-0 mt-0.5" data-icon="mdi:check-circle"></span>
                                <div>
                                    <p class="text-[11px] font-bold text-emerald-800">Página 2 activa</p>
                                    <p class="text-[10px] font-semibold text-emerald-700/80">El PDF se generará con 2 hojas.</p>
                                </div>
                            </div>
                            <label class="flex items-center gap-2 text-[11px] font-bold text-gray-600 cursor-pointer px-1">
                                <input type="checkbox" name="remove_background_back" value="1" class="rounded border-gray-300 text-primary focus:ring-primary/30">
                                Quitar página 2 al guardar
                            </label>
                        @else
                            <div class="flex items-start gap-2 rounded-xl bg-gray-100/80 border border-gray-200/80 px-3 py-2.5">
                                <span class="iconify text-lg text-gray-400 shrink-0 mt-0.5" data-icon="mdi:file-document-outline"></span>
                                <div>
                                    <p class="text-[11px] font-bold text-gray-700">Solo página 1</p>
                                    <p class="text-[10px] font-semibold text-gray-500">Sube una imagen para añadir la segunda hoja.</p>
                                </div>
                            </div>
                        @endif

                        @include('certificate_templates.partials.upload-zone', [
                            'name' => 'background_back',
                            'inputId' => 'upload-background-back-edit',
                            'label' => 'Página 2',
                            'optional' => true,
                            'hint' => 'Mismo formato A4 que la página 1',
                            'existingPreview' => $bgBackUrl !== '' ? $bgBackUrl : null,
                        ])
                    </div>

                    <p class="text-[10px] font-extrabold text-primary/60 uppercase tracking-widest">Campos de la página 1</p>

                    @foreach ($editorRows as $row)
                        @php $fk = $row['field_key']; @endphp
                        <details data-cert-panel="{{ $fk }}"
                            class="cert-field-details rounded-2xl border border-gray-100 bg-white overflow-hidden [&:open]:border-primary/25 [&:open]:shadow-[0_8px_24px_rgba(0,0,0,0.04)]">
                            <summary
                                class="cursor-pointer select-none list-none px-4 py-3 flex items-center justify-between gap-2 bg-gray-50/90 hover:bg-gray-50 transition-colors [&::-webkit-details-marker]:hidden">
                                <span class="text-[11px] font-black text-gray-900">{{ $row['label'] }}</span>
                                <span class="cert-details-chevron iconify shrink-0 text-xl text-primary/35 transition-transform duration-200"
                                    data-icon="mdi:chevron-down"></span>
                            </summary>
                            <div class="p-4 space-y-3 border-t border-gray-100/90">
                                <input type="hidden" name="fields[{{ $fk }}][field_key]" value="{{ $fk }}">
                                <input type="hidden" name="fields[{{ $fk }}][x]" value="{{ old('fields.'.$fk.'.x', $row['x']) }}" data-axis="x">
                                <input type="hidden" name="fields[{{ $fk }}][y]" value="{{ old('fields.'.$fk.'.y', $row['y']) }}" data-axis="y">

                                <div>
                                    <label class="text-[9px] font-bold text-gray-400 uppercase">Ancho</label>
                                    <input type="number" step="1" name="fields[{{ $fk }}][width]"
                                        value="{{ old('fields.'.$fk.'.width', $row['width']) }}" required
                                        data-axis="width"
                                        class="js-cert-sync w-full px-2 py-2 rounded-xl border border-gray-100 text-xs font-mono">
                                </div>
                                <div>
                                    <label class="text-[9px] font-bold text-gray-400 uppercase">Tamaño fuente (diseño)</label>
                                    <input type="number" step="1" name="fields[{{ $fk }}][font_size]"
                                        value="{{ old('fields.'.$fk.'.font_size', $row['font_size']) }}" required
                                        data-axis="font_size"
                                        class="js-cert-sync w-full px-2 py-2 rounded-xl border border-gray-100 text-xs font-mono">
                                </div>
                                <div class="flex gap-3 items-center">
                                    <div class="flex-1">
                                        <label class="text-[9px] font-bold text-gray-400 uppercase">Color</label>
                                        <input type="color" name="fields[{{ $fk }}][font_color]"
                                            value="{{ old('fields.'.$fk.'.font_color', $row['font_color']) }}" required
                                            data-axis="font_color"
                                            class="js-cert-sync h-10 w-full rounded-xl border border-gray-100 cursor-pointer">
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-[9px] font-bold text-gray-400 uppercase">Peso</label>
                                        <select name="fields[{{ $fk }}][font_weight]"
                                            class="js-cert-sync w-full px-2 py-2 rounded-xl border border-gray-100 text-xs font-bold"
                                            data-axis="font_weight">
                                            @foreach (['normal', 'bold'] as $w)
                                                <option value="{{ $w }}" @selected(old('fields.'.$fk.'.font_weight', $row['font_weight']) === $w)>{{ $w }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[9px] font-bold text-gray-400 uppercase">Fuente</label>
                                    <select name="fields[{{ $fk }}][font_family]"
                                        class="js-cert-sync w-full px-2 py-2 rounded-xl border border-gray-100 text-xs font-bold"
                                        data-axis="font_family">
                                        @foreach ($certificateFontFamilies as $fontKey => $fam)
                                            <option value="{{ $fontKey }}" @selected(old('fields.'.$fk.'.font_family', $row['font_family'] ?? 'dejavu_sans') === $fontKey)>{{ $fam['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </details>
                    @endforeach
                </aside>
            </form>
        </main>
    </div>

    <style>
        .cert-field-details[open] summary .cert-details-chevron {
            transform: rotate(180deg);
        }

        /* Misma caja de texto que en pdf/certificate.blade.php (.field) */
        .cert-field {
            display: block;
            line-height: 1;
            background: rgba(0, 0, 0, 0.45);
            outline: 2px dashed rgba(255, 255, 255, 0.9);
            outline-offset: -2px;
            border: none;
            border-radius: 0.25rem;
            vertical-align: top;
        }

        .cert-field:hover,
        .cert-field.ring-4 {
            outline-color: rgb(52, 211, 153);
            background: rgba(0, 0, 0, 0.55);
        }
    </style>

    <style>
        @foreach ($certificateFontFamilies as $fam)
            @foreach ($fam['faces'] ?? [] as $face)
                @if (! empty($face['path']))
                    @font-face {
                        font-family: '{{ $fam['css_family'] }}';
                        src: url('{{ asset($face['path']) }}') format('truetype');
                        font-weight: {{ $face['weight'] ?? 'normal' }};
                        font-style: {{ $face['style'] ?? 'normal' }};
                    }
                @endif
            @endforeach
        @endforeach
    </style>

    <script>
        (function () {
            const CERT_FONT_CSS = @json(collect($certificateFontFamilies)->map(fn ($f) => $f['css_family'])->all());
            const DESIGN_W = {{ (int) $DW }};
            const DESIGN_H = {{ (int) $DH }};
            const form = document.getElementById('certificate-editor-form');
            const inner = document.getElementById('cert-inner');
            const bgFront = document.getElementById('cert-bg-front');
            const bgBack = document.getElementById('cert-bg-back');
            const bgFrontEmpty = document.getElementById('cert-bg-front-empty');
            const bgBackEmpty = document.getElementById('cert-bg-back-empty');
            const sideTabs = document.querySelectorAll('[data-cert-side]');
            if (!form || !inner) return;

            let activeSide = 'front';

            function setActiveSide(side) {
                activeSide = side;
                const isFront = side === 'front';

                if (bgFront) bgFront.classList.toggle('hidden', !isFront || !bgFront.getAttribute('src'));
                if (bgBack) bgBack.classList.toggle('hidden', isFront || !bgBack.getAttribute('src'));
                if (bgFrontEmpty) bgFrontEmpty.classList.toggle('hidden', !isFront || (bgFront && bgFront.getAttribute('src')));
                if (bgBackEmpty) bgBackEmpty.classList.toggle('hidden', isFront || (bgBack && bgBack.getAttribute('src')));

                inner.querySelectorAll('[data-cert-field]').forEach((btn) => {
                    btn.classList.toggle('hidden', !isFront);
                    btn.style.pointerEvents = isFront ? '' : 'none';
                });

                sideTabs.forEach((tab) => {
                    const active = tab.getAttribute('data-cert-side') === side;
                    tab.classList.toggle('border-primary', active);
                    tab.classList.toggle('bg-primary/10', active);
                    tab.classList.toggle('text-primary', active);
                    tab.classList.toggle('border-gray-200', !active);
                    tab.classList.toggle('text-gray-500', !active);
                });
            }

            sideTabs.forEach((tab) => {
                tab.addEventListener('click', () => setActiveSide(tab.getAttribute('data-cert-side') || 'front'));
            });

            const backFileInput = form.querySelector('[name="background_back"]');
            if (backFileInput) {
                backFileInput.addEventListener('change', () => {
                    const file = backFileInput.files && backFileInput.files[0];
                    if (!file || !bgBack) return;
                    const url = URL.createObjectURL(file);
                    bgBack.src = url;
                    bgBack.classList.remove('hidden');
                    if (bgBackEmpty) bgBackEmpty.classList.add('hidden');
                    setActiveSide('back');
                });
            }

            setActiveSide('front');

            function fieldHeight(fontSize) {
                return Number(fontSize);
            }

            function readField(key) {
                const num = (axis) => {
                    const el = form.querySelector('[name="fields[' + key + '][' + axis + ']"]');
                    return el ? parseFloat(el.value) : 0;
                };
                return {
                    key,
                    x: num('x'),
                    y: num('y'),
                    width: num('width'),
                    font_size: num('font_size'),
                    font_color: (form.querySelector('[name="fields[' + key + '][font_color]"]') || {}).value || '#000000',
                    font_weight: (form.querySelector('[name="fields[' + key + '][font_weight]"]') || {}).value || 'normal',
                    font_family: (form.querySelector('[name="fields[' + key + '][font_family]"]') || {}).value || 'dejavu_sans',
                };
            }

            function writeXY(key, x, y) {
                const xi = form.querySelector('[name="fields[' + key + '][x]"]');
                const yi = form.querySelector('[name="fields[' + key + '][y]"]');
                if (xi) xi.value = Math.round(x);
                if (yi) yi.value = Math.round(y);
            }

            function syncBoxes() {
                inner.querySelectorAll('[data-cert-field]').forEach((btn) => {
                    const key = btn.getAttribute('data-cert-field');
                    const f = readField(key);
                    const h = fieldHeight(f.font_size);

                    btn.style.left = (f.x / DESIGN_W * 100) + '%';
                    btn.style.top = (f.y / DESIGN_H * 100) + '%';
                    btn.style.width = (f.width / DESIGN_W * 100) + '%';
                    const cqw = (f.font_size / DESIGN_W) * 100;
                    btn.style.fontSize = 'calc(' + cqw + ' * 1cqw)';
                    btn.style.fontWeight = f.font_weight;
                    const cssFam = CERT_FONT_CSS[f.font_family];
                    btn.style.fontFamily = cssFam ? ("'" + cssFam + "', DejaVu Sans, sans-serif") : 'DejaVu Sans, sans-serif';
                    btn.style.color = '#ffffff';
                    btn.style.textAlign = 'left';
                    btn.style.height = (h / DESIGN_H * 100) + '%';
                    btn.style.lineHeight = '1';
                    btn.style.padding = '0';
                    btn.style.margin = '0';
                    btn.style.boxSizing = 'border-box';
                    btn.style.display = 'block';

                    btn.classList.remove('ring-4', 'ring-emerald-400', 'z-20');
                    btn.classList.add('z-10');
                });
            }

            form.querySelectorAll('.js-cert-sync').forEach((el) => {
                el.addEventListener('input', syncBoxes);
                el.addEventListener('change', syncBoxes);
            });

            let drag = null;

            inner.querySelectorAll('[data-cert-field]').forEach((btn) => {
                btn.addEventListener('pointerdown', (ev) => {
                    if (activeSide !== 'front') return;
                    ev.preventDefault();
                    const key = btn.getAttribute('data-cert-field');
                    btn.setPointerCapture(ev.pointerId);
                    inner.querySelectorAll('[data-cert-field]').forEach((b) =>
                        b.classList.remove('ring-4', 'ring-emerald-400', 'z-20')
                    );
                    btn.classList.add('ring-4', 'ring-emerald-400', 'z-20');

                    const f = readField(key);
                    const rect = inner.getBoundingClientRect();
                    const scaleX = DESIGN_W / rect.width;
                    const scaleY = DESIGN_H / rect.height;
                    const mx = (ev.clientX - rect.left) * scaleX;
                    const my = (ev.clientY - rect.top) * scaleY;

                    drag = {
                        key,
                        offsetX: mx - f.x,
                        offsetY: my - f.y,
                    };
                });

                btn.addEventListener('pointermove', (ev) => {
                    if (!drag || drag.key !== btn.getAttribute('data-cert-field')) return;
                    const rect = inner.getBoundingClientRect();
                    const scaleX = DESIGN_W / rect.width;
                    const scaleY = DESIGN_H / rect.height;
                    const mx = (ev.clientX - rect.left) * scaleX;
                    const my = (ev.clientY - rect.top) * scaleY;

                    const f = readField(drag.key);
                    const h = fieldHeight(f.font_size);
                    let nx = mx - drag.offsetX;
                    let ny = my - drag.offsetY;
                    nx = Math.max(0, Math.min(nx, DESIGN_W - f.width));
                    ny = Math.max(0, Math.min(ny, DESIGN_H - h));

                    writeXY(drag.key, nx, ny);
                    syncBoxes();
                });

                const endDrag = (ev) => {
                    if (!drag) return;
                    try {
                        btn.releasePointerCapture(ev.pointerId);
                    } catch (e) { /* noop */ }
                    drag = null;
                };

                btn.addEventListener('pointerup', endDrag);
                btn.addEventListener('pointercancel', endDrag);
            });

            window.addEventListener('resize', syncBoxes);
            syncBoxes();
        })();
    </script>
@endsection
