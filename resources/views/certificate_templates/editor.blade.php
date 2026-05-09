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
                Arrastra cada etiqueta sobre la imagen; a la derecha ajustas ancho, fuente por campo, tamaño y color (texto siempre alineado a la izquierda en el PDF).
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
                class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_340px] gap-6 items-start">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-4 sm:p-6 overflow-hidden">
                    <div id="cert-canvas" class="rounded-2xl border border-gray-200 bg-gray-100 overflow-hidden select-none">
                        <div id="cert-inner"
                            class="relative w-full max-h-[70vh] mx-auto bg-neutral-800"
                            style="aspect-ratio: {{ $DW }} / {{ $DH }}; container-type: inline-size;">
                            @if ($bgUrl !== '')
                                <img src="{{ $bgUrl }}" alt="Fondo certificado"
                                    class="absolute inset-0 w-full h-full object-cover pointer-events-none">
                            @else
                                <div
                                    class="absolute inset-0 flex items-center justify-center bg-neutral-700 text-white/70 text-xs font-bold px-4 text-center pointer-events-none">
                                    Sin imagen de fondo en BD
                                </div>
                            @endif
                            @foreach ($editorRows as $row)
                                @php
                                    $k = $row['field_key'];
                                @endphp
                                <button type="button" data-cert-field="{{ $k }}"
                                    class="cert-field absolute z-10 rounded-lg border-2 border-dashed border-white/90 bg-black/50 px-1 py-0.5 text-white shadow-lg cursor-grab active:cursor-grabbing text-left overflow-hidden leading-tight hover:bg-black/65 hover:border-emerald-300 transition-colors">
                                    <span class="pointer-events-none block truncate">{{ $row['label'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <aside class="bg-white rounded-3xl border border-gray-100 shadow-xl p-6 space-y-5 lg:sticky lg:top-6">
                    <div>
                        <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-widest mb-1.5">Nombre de la plantilla</label>
                        <input type="text" name="name" value="{{ old('name', $template->name) }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-gray-100 text-xs font-bold focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary/40">
                    </div>

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
            if (!form || !inner) return;

            function fieldHeight(fontSize) {
                return Math.max(56, Number(fontSize) * 1.35);
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
                    btn.style.lineHeight = '1.1';

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
