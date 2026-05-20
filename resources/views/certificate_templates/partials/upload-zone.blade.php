@php
    $inputId = $inputId ?? 'upload-' . $name;
    $required = $required ?? false;
    $optional = $optional ?? false;
    $hint = $hint ?? 'A4 apaisado · JPG, PNG o WebP · máx. 2 MB';
    $existingPreview = $existingPreview ?? null;
    $hasExisting = filled($existingPreview);
@endphp

<div class="cert-upload" data-cert-upload>
    <div class="flex items-center justify-between gap-2 mb-2">
        <span class="text-[10px] font-extrabold text-primary/60 uppercase tracking-widest">{{ $label }}</span>
        @if ($optional)
            <span class="text-[9px] font-bold uppercase tracking-wide text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Opcional</span>
        @elseif ($required)
            <span class="text-[9px] font-bold uppercase tracking-wide text-primary bg-primary/10 px-2 py-0.5 rounded-full">Requerida</span>
        @endif
    </div>

    <label for="{{ $inputId }}"
        class="cert-upload-zone group relative block rounded-2xl border-2 border-dashed border-gray-200 bg-gradient-to-b from-gray-50/90 to-white overflow-hidden cursor-pointer transition-all duration-200 hover:border-primary/40 hover:bg-primary-light/30 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10 cert-upload-zone--drag">
        <input type="file" id="{{ $inputId }}" name="{{ $name }}" accept="image/jpeg,image/png,image/webp"
            @required($required) class="sr-only cert-upload-input">

        {{-- Vista previa (archivo nuevo o imagen ya guardada) --}}
        <div class="cert-upload-preview @if (! $hasExisting) hidden @endif">
            <div class="aspect-[297/210] w-full bg-neutral-800 relative">
                <img src="{{ $existingPreview ?? '' }}" alt="" class="cert-upload-img absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 right-0 p-3 flex items-end justify-between gap-2">
                    <p class="cert-upload-filename text-[10px] font-bold text-white truncate max-w-[65%] drop-shadow">
                        @if ($hasExisting) Imagen actual @endif
                    </p>
                    <span class="shrink-0 text-[9px] font-black uppercase tracking-wide text-white/90 bg-white/20 backdrop-blur px-2 py-1 rounded-lg border border-white/20">
                        Cambiar
                    </span>
                </div>
            </div>
        </div>

        {{-- Estado vacío --}}
        <div class="cert-upload-empty @if ($hasExisting) hidden @endif px-5 py-8 sm:py-10 flex flex-col items-center text-center">
            <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                <span class="iconify text-3xl" data-icon="line-md:upload-loop"></span>
            </div>
            <p class="text-xs font-black text-gray-800">Arrastra tu imagen aquí</p>
            <p class="text-[11px] font-semibold text-gray-500 mt-1">o haz clic para elegir archivo</p>
            <p class="text-[10px] text-gray-400 mt-3">{{ $hint }}</p>
        </div>
    </label>

    @error($name)
        <p class="text-red-500 text-[10px] font-bold mt-2 flex items-center gap-1">
            <span class="iconify text-sm" data-icon="mdi:alert-circle"></span>
            {{ $message }}
        </p>
    @enderror
</div>

@once
    @push('scripts')
        <script>
            (function () {
                function initCertUpload(root) {
                    const input = root.querySelector('.cert-upload-input');
                    const zone = root.querySelector('.cert-upload-zone');
                    const empty = root.querySelector('.cert-upload-empty');
                    const preview = root.querySelector('.cert-upload-preview');
                    const img = root.querySelector('.cert-upload-img');
                    const filename = root.querySelector('.cert-upload-filename');
                    if (!input || !zone) return;

                    let objectUrl = null;

                    function revokeUrl() {
                        if (objectUrl) {
                            URL.revokeObjectURL(objectUrl);
                            objectUrl = null;
                        }
                    }

                    function showPreview(url, name) {
                        if (!preview || !img) return;
                        img.src = url;
                        if (filename) filename.textContent = name || 'Imagen seleccionada';
                        preview.classList.remove('hidden');
                        empty?.classList.add('hidden');
                        zone.classList.remove('cert-upload-zone--drag');
                        zone.classList.add('border-primary/30', 'border-solid');
                    }

                    function showEmpty() {
                        revokeUrl();
                        preview?.classList.add('hidden');
                        empty?.classList.remove('hidden');
                        zone.classList.add('cert-upload-zone--drag');
                        zone.classList.remove('border-primary/30', 'border-solid');
                        input.value = '';
                    }

                    input.addEventListener('change', () => {
                        const file = input.files && input.files[0];
                        if (!file) return;
                        revokeUrl();
                        objectUrl = URL.createObjectURL(file);
                        showPreview(objectUrl, file.name);
                    });

                    ['dragenter', 'dragover'].forEach((ev) => {
                        zone.addEventListener(ev, (e) => {
                            e.preventDefault();
                            zone.classList.add('border-primary', 'bg-primary-light/50');
                        });
                    });

                    ['dragleave', 'drop'].forEach((ev) => {
                        zone.addEventListener(ev, (e) => {
                            e.preventDefault();
                            zone.classList.remove('border-primary', 'bg-primary-light/50');
                        });
                    });

                    zone.addEventListener('drop', (e) => {
                        const file = e.dataTransfer?.files?.[0];
                        if (!file || !file.type.startsWith('image/')) return;
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        input.files = dt.files;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                }

                document.querySelectorAll('[data-cert-upload]').forEach(initCertUpload);
            })();
        </script>
    @endpush
@endonce
