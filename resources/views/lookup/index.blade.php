@extends('appsita')

@section('title', 'Consulta de Cursos')

@section('content')
    {{-- Header --}}
    <section class="bg-white rounded-2xl p-6 mt-6 shadow-md text-center">
        <img src="/innova-food.ico" alt="InnovaFood" class="w-12 h-12 mx-auto mb-3">
        <h1 class="text-primary text-2xl font-bold mb-1">Consulta de Cursos</h1>
        <p class="text-gray-500 text-sm">Ingresa tu cédula para ver los cursos que has completado.</p>
    </section>

    {{-- Search form --}}
    <section class="bg-white rounded-2xl p-6 mt-4 shadow-md">
        <form method="POST" action="{{ route('lookup.search') }}" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <div class="flex-1">
                <label for="cedula" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Número de cédula</label>
                <input
                    id="cedula"
                    type="text"
                    name="cedula"
                    value="{{ old('cedula', $cedula ?? '') }}"
                    placeholder="Ej. 0912345678"
                    maxlength="20"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                    autofocus
                >
                @error('cedula')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="w-full sm:w-auto bg-primary hover:bg-primary-hover text-white py-2.5 px-7 rounded-xl font-bold transition-colors flex items-center justify-center gap-2 text-sm">
                    <span class="iconify w-4 h-4" data-icon="line-md:search"></span>
                    Consultar
                </button>
            </div>
        </form>
    </section>

    {{-- Results --}}
    @isset($records)
        <section class="bg-white rounded-2xl shadow-md mt-4 overflow-hidden">
            @if ($records->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                    <span class="iconify w-12 h-12 mb-3" data-icon="line-md:account-small"></span>
                    <p class="text-sm font-medium">No se encontraron registros para la cédula <span class="font-mono">{{ $cedula }}</span>.</p>
                    <p class="text-xs mt-1">Verifica el número ingresado e intenta nuevamente.</p>
                </div>
            @else
                {{-- Person header --}}
                <div class="bg-primary/5 border-b border-primary/10 px-6 py-4 flex items-center gap-3">
                    <span class="iconify w-8 h-8 text-primary" data-icon="line-md:account"></span>
                    <div>
                        <p class="font-bold text-primary text-base">{{ $person->full_name }}</p>
                        <p class="text-xs text-gray-500 font-mono">C.I. {{ $person->id_card }}</p>
                    </div>
                </div>

                {{-- Courses list --}}
                <div class="divide-y divide-gray-100">
                    @foreach ($records as $record)
                        @php
                            $isExpired = $record->expires_at->isPast();
                        @endphp
                        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3 hover:bg-gray-50 transition-colors">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 text-sm">{{ $record->course_name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $record->subscription_type }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1 rounded-full
                                    {{ $isExpired ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    <span class="iconify w-3.5 h-3.5"
                                        data-icon="{{ $isExpired ? 'line-md:check-all' : 'line-md:clock' }}"></span>
                                    {{ $isExpired ? 'Completado' : 'Vigente' }}
                                </span>
                                <p class="text-xs text-gray-400 mt-1">Vence: {{ $record->expires_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    @endisset
@endsection
