@extends('appsita')

@section('title', 'Consulta de Cursos')

@section('content')
    <div class="min-h-screen bg-[#faf8f7] flex flex-col font-sans">

        {{-- Hero Section --}}
        <header class="relative bg-primary-dark pt-14 pb-20 px-6">
            {{-- 1. Contenedor de decoraciones (Aislamos el overflow aquí) --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 opacity-5"
                    style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-primary/20 rounded-full blur-3xl"></div>
            </div>

            {{-- 2. Contenido del Hero --}}
            <div class="relative max-w-3xl mx-auto text-center z-10">
                <div class="flex flex-col items-center mb-8">
                    <div
                        class="w-28 md:w-44 aspect-square bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl flex items-center justify-center shadow-lg mb-3 overflow-hidden">
                        <img src="/InnovaFood_Logo.png" alt="Logo" class="size-full object-contain p-2">
                    </div>
                    <a href="{{ route('login') }}"
                        class="text-white/60 hover:text-white text-md font-extrabold tracking-widest uppercase transition-colors">
                        {{ config('app.name') }}
                    </a>
                </div>

                <h1 class="text-white text-4xl md:text-5xl font-black leading-tight">
                    Consulta tu historial <span class="text-white/40 block">de cursos</span>
                </h1>
            </div>

            {{-- 3. Search Form (Ahora sí es visible porque el padre NO tiene overflow-hidden) --}}
            <div class="absolute left-1/2 -bottom-8 -translate-x-1/2 w-full max-w-2xl px-6 z-20">
                <form method="POST" action="{{ route('lookup.search') }}"
                    class="group flex bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transition-all focus-within:ring-4 focus-within:ring-primary/10">
                    @csrf
                    <div class="flex-1 flex items-center gap-3 px-5">
                        <span class="iconify text-gray-400 text-xl" data-icon="line-md:account-small"></span>
                        <input id="cedula" type="text" name="cedula" value="{{ old('cedula', $cedula ?? '') }}"
                            placeholder="Número de cédula..." maxlength="20" autofocus
                            class="w-full py-5 text-sm font-medium focus:outline-none bg-transparent">
                    </div>
                    <button type="submit"
                        class="bg-primary hover:bg-primary-hover text-white font-bold px-8 transition-colors flex items-center gap-2">
                        <span class="iconify text-lg" data-icon="line-md:search"></span>
                        <span class="hidden sm:inline">Consultar</span>
                    </button>
                </form>
                @error('cedula')
                    <p
                        class="text-red-500 text-xs font-bold mt-3 ml-2 flex items-center gap-1 bg-[#faf8f7] inline-block px-2 py-1 rounded-lg">
                        <span class="iconify" data-icon="line-md:alert-circle"></span> {{ $message }}
                    </p>
                @enderror
            </div>
        </header>

        {{-- Results Main --}}
        <main class="flex-1 max-w-2xl w-full mx-auto px-6 pt-20 pb-12">
            @isset($records)
                @if ($records->isEmpty())
                    <div class="text-center py-16 opacity-60">
                        <div
                            class="bg-white w-16 h-16 mx-auto rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center mb-4">
                            <span class="iconify text-3xl text-gray-300" data-icon="line-md:search-minus"></span>
                        </div>
                        <h3 class="font-bold text-gray-700">No hay registros</h3>
                        <p class="text-sm">No encontramos cursos vinculados a: <span
                                class="font-mono text-primary">{{ $cedula }}</span></p>
                    </div>
                @else
                    {{-- Profile Card --}}
                    <div class="text-center mb-10">
                        <div class="inline-flex flex-col items-center">
                            <h2 class="text-xl font-black text-gray-800 tracking-tight leading-none mb-1">{{ $person->full_name }}
                            </h2>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">C.I. {{ $person->id_card }}
                            </p>
                            <div class="bg-primary/5 px-3 py-1 rounded-full border border-primary/10">
                                <span class="text-xs font-black text-primary">{{ $records->count() }}
                                    {{ $records->count() === 1 ? 'Curso' : 'Cursos' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Course Timeline --}}
                    <div class="relative max-w-lg mx-auto">
                        {{-- Connecting Line --}}
                        <div
                            class="absolute left-6 top-6 bottom-6 w-0.5 bg-gradient-to-b from-primary/30 via-primary/10 to-transparent hidden sm:block">
                        </div>

                        @foreach ($records as $record)
                            @php
                                $isExpired = $record->expires_at->isPast();
                                $styles = $isExpired ? [
                                    'bg' => 'bg-orange-500 shadow-orange-200',
                                    'icon' => 'line-md:alert-circle',
                                    'text' => 'text-orange-600',
                                    'label' => 'Caducado'
                                ] : [
                                    'bg' => 'bg-green-500 shadow-green-200',
                                    'icon' => 'line-md:check-all',
                                    'text' => 'text-green-600',
                                    'label' => 'Vigente'
                                ];
                            @endphp
                            <div class="relative flex sm:gap-6 items-start mb-8 last:mb-0 group">
                                {{-- Timeline Node --}}
                                <div
                                    class="relative z-10 w-14 h-14 rounded-2xl hidden sm:flex items-center justify-center shrink-0 border-4 border-[#faf8f7] transition-transform duration-300 group-hover:scale-110 shadow-sm {{ $styles['bg'] }} text-white">
                                    <span class="iconify text-2xl" data-icon="{{ $styles['icon'] }}"></span>
                                </div>

                                {{-- Card Info --}}
                                <div
                                    class="flex-1 bg-white rounded-2xl border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.06)] hover:border-white transition-all duration-300 flex flex-col overflow-hidden">
                                    {{-- Top: Course Title Section --}}
                                    <div class="p-6 flex-1">
                                        <div class="mb-5">
                                            <span
                                                class="text-xs font-extrabold text-primary/60 uppercase tracking-widest flex items-center gap-1.5 mb-1.5 align-middle">
                                                <span class="iconify text-sm" data-icon="line-md:document-list"></span> Curso /
                                                Capacitación
                                            </span>
                                            <h4
                                                class="font-black text-gray-900 text-base md:text-lg lg:text-xl leading-snug group-hover:text-primary transition-colors">
                                                {{ $record->course_name }}
                                            </h4>
                                        </div>

                                        {{-- Middle: Details Grid --}}
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                                            <div>
                                                <span
                                                    class="text-xs font-extrabold text-primary/60 uppercase tracking-widest block mb-1">Tipo
                                                    de Registro</span>
                                                <p class="text-base font-black text-gray-800 capitalize">
                                                    {{ $record->subscription_type }}
                                                </p>
                                            </div>
                                            <div>
                                                <span
                                                    class="text-xs font-extrabold text-primary/60 uppercase tracking-widest block mb-1">Estado
                                                    de Vigencia</span>
                                                <span
                                                    class="inline-flex text-sm font-black uppercase tracking-wider {{ $styles['text'] }}">
                                                    {{ $styles['label'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Bottom: Footer style --}}
                                    <div
                                        class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-extrabold text-primary/60 uppercase tracking-wider">Fecha de
                                            Caducidad</span>
                                        <time
                                            class="text-sm font-black text-gray-800 font-mono">{{ $record->expires_at->format('d/m/Y') }}</time>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="text-center py-20">
                    <span class="iconify text-5xl text-gray-200 mx-auto mb-4" data-icon="line-md:document-list"></span>
                    <p class="text-gray-400 font-medium">Introduce tu identificación para comenzar la búsqueda.</p>
                </div>
            @endisset
        </main>
    </div>
@endsection