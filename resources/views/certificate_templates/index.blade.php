@extends('appsita')

@section('title', 'Plantillas de certificado')

@section('content')
    <div class="min-h-screen bg-[#faf8f7] flex flex-col font-sans relative">

        <header class="relative bg-primary-dark pt-10 pb-24 px-6">
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 opacity-5"
                    style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
            </div>

            <div class="relative max-w-4xl mx-auto flex justify-between items-center mb-10 z-10">
                <div class="flex items-center gap-2">
                    <div
                        class="w-10 h-10 aspect-square bg-white rounded-xl flex items-center justify-center overflow-hidden">
                        <img src="/InnovaFood_Logo.png" class="size-full object-contain p-1" alt="">
                    </div>
                    <span class="text-white text-md font-black tracking-tight">{{ config('app.name') }}</span>
                </div>
                <a href="{{ route('clients.index') }}"
                    class="bg-white/10 hover:bg-white/20 text-white py-2 px-4 rounded-xl font-bold text-xs transition-colors flex items-center gap-1.5 border border-white/10">
                    <span class="iconify" data-icon="line-md:arrow-left"></span> Clientes
                </a>
            </div>

            <div class="relative max-w-4xl mx-auto text-center z-10">
                <h1 class="text-white text-3xl font-black">Plantillas de <span class="text-white/40">certificado</span></h1>
                <p class="text-white/60 text-xs font-semibold mt-2 max-w-2xl mx-auto leading-relaxed">Sube una imagen A4 apaisado como fondo. Después podrás <strong class="text-white/75">arrastrar y alinear</strong> cada dato (cédula, nombres, apellidos, curso, horas y fecha) sobre la vista previa, igual que en los carnets de Ligatactica.</p>
            </div>
        </header>

        <main class="flex-1 max-w-4xl w-full mx-auto px-6 -mt-12 pb-12 z-20 space-y-8">

            @if (session('success'))
                <div
                    class="bg-green-50 border border-green-100 text-green-800 text-xs font-bold px-4 py-3 rounded-2xl shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_20px_60px_rgba(0,0,0,0.04)] p-8">
                <h2 class="text-sm font-black text-gray-900 mb-4 flex items-center gap-2">
                    <span class="iconify text-primary" data-icon="line-md:upload-loop"></span>
                    Nueva plantilla
                </h2>
                <form method="POST" action="{{ route('certificate-templates.store') }}" enctype="multipart/form-data"
                    class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-widest mb-1.5">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-gray-100 text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary/40"
                            placeholder="Ej. Curso manipulación de alimentos">
                        @error('name')
                            <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-widest mb-1.5">Imagen de fondo</label>
                        <input type="file" name="background" accept="image/jpeg,image/png,image/webp" required
                            class="w-full text-xs font-semibold text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-primary file:text-white file:font-bold">
                        <p class="text-[10px] text-gray-400 mt-1">Máx. ~30 MB en la aplicación; PHP suele limitar a 2 MB por defecto. Para desarrollo ejecuta <code class="font-mono bg-gray-100 px-1 rounded">composer dev</code> (sube límites). En producción ajusta php.ini o Nginx <code class="font-mono bg-gray-100 px-1 rounded">client_max_body_size</code>.</p>
                        @error('background')
                            <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2 flex justify-end">
                        <button type="submit"
                            class="bg-primary hover:bg-primary-hover text-white font-black py-3 px-8 rounded-2xl shadow-md shadow-primary/10 hover:shadow-lg transition-all flex items-center gap-2">
                            <span class="iconify text-lg" data-icon="line-md:confirm"></span>
                            Crear y abrir editor
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.02)] overflow-hidden">
                @if ($templates->isEmpty())
                    <div class="p-16 text-center text-gray-400 text-sm font-bold">
                        Aún no hay plantillas. Sube la primera arriba.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100/60">
                                    <th class="px-6 py-4 text-[11px] font-extrabold text-primary/60 uppercase tracking-widest">
                                        Nombre</th>
                                    <th class="px-6 py-4 text-[11px] font-extrabold text-primary/60 uppercase tracking-widest text-right">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($templates as $template)
                                    <tr class="hover:bg-primary-light/10 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-xs font-bold text-gray-900">{{ $template->name }}</p>
                                            @php
                                                $approxKb = $template->background_base64
                                                    ? round(strlen($template->background_base64) * 0.75 / 1024, 1)
                                                    : null;
                                            @endphp
                                            <p class="text-[10px] text-gray-400 mt-0.5">
                                                <span class="font-mono">{{ $template->background_mime ?? '—' }}</span>
                                                @if ($approxKb !== null)
                                                    <span class="text-gray-400"> · ~{{ $approxKb }} KB en BD</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2 flex-wrap">
                                                <a href="{{ route('certificate-templates.edit', $template) }}"
                                                    class="p-2 rounded-xl bg-primary/10 text-primary hover:bg-primary hover:text-white shadow-sm transition-all inline-flex"
                                                    title="Editor visual">
                                                    <span class="iconify text-lg" data-icon="line-md:pencil"></span>
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('certificate-templates.destroy', $template) }}"
                                                    class="inline"
                                                    onsubmit="return confirm('¿Eliminar esta plantilla?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white shadow-sm transition-all inline-flex"
                                                        title="Eliminar">
                                                        <span class="iconify text-lg" data-icon="line-md:remove"></span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </main>
    </div>
@endsection
