@extends('appsita')

@section('title', 'Iniciar sesión')

@php
    $inputClass = 'w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary/5 focus:border-primary/40 transition-all';
    $labelClass = 'text-xs font-extrabold text-primary/60 uppercase tracking-widest flex items-center gap-1.5 mb-1.5 align-middle';
@endphp

@section('content')
    <div class="min-h-screen bg-[#faf8f7] flex flex-col font-sans relative flex items-center justify-center p-4">

        {{-- Decoraciones de Fondo (Anillos y Luces) --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-hover/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-1/4 -left-20 w-80 h-80 bg-primary/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-primary/20 rounded-full border border-white/5 opacity-30"></div>
        </div>

        {{-- Form Container --}}
        <div class="w-full max-w-md bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_20px_60px_rgba(0,0,0,0.05)] z-10">
            <h1 class="text-primary text-center text-3xl font-black mb-1 flex items-center justify-center gap-2">
                {{ env('APP_NAME') }}<span class="text-xs border border-primary/30 px-2 py-0.5 rounded-full align-middle font-bold text-primary/60 tracking-wider">G.C</span>
            </h1>
            <p class="text-gray-400 text-center text-sm mb-6">Panel de Administración</p>

            @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
                <div class="bg-red-50 text-red-700 p-4 mb-6 rounded-xl border border-red-100 text-xs font-medium flex items-center gap-2">
                    <span class="iconify text-base" data-icon="line-md:alert-circle"></span>
                    Algo salió mal. Por favor intenta de nuevo.
                </div>
            @endif

            <form method="POST" action="{{ route('login.try') }}" class="flex flex-col gap-5">
                @csrf

                <div>
                    <label for="email" class="{{ $labelClass }}">
                        <span class="iconify text-sm" data-icon="line-md:email"></span> Correo electrónico
                    </label>
                    <div class="relative">
                        <span class="iconify absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-base" data-icon="line-md:email"></span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@empresa.com" class="{{ $inputClass }} {{ $errors->has('email') ? 'border-red-400' : '' }}">
                    </div>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="{{ $labelClass }}">
                        <span class="iconify text-sm" data-icon="line-md:lock"></span> Contraseña
                    </label>
                    <div class="relative">
                        <span class="iconify absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-base" data-icon="line-md:lock"></span>
                        <input id="password" type="password" name="password" required placeholder="••••••••" class="{{ $inputClass }} {{ $errors->has('password') ? 'border-red-400' : '' }}">
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="mt-2 w-full bg-primary hover:bg-primary-hover text-white py-3 rounded-full font-black text-sm shadow-md shadow-primary/10 hover:shadow-lg hover:shadow-primary/20 transition-all flex items-center justify-center gap-2">
                    <span class="iconify text-base" data-icon="line-md:login"></span> Iniciar sesión
                </button>
            </form>

            <div class="mt-5 pt-4 border-t border-gray-100/60 flex justify-center">
                <a href="{{ route('consulta') }}" class="text-xs font-bold text-primary/60 hover:text-primary flex items-center gap-1.5 align-middle transition-all">
                    <span class="iconify text-sm" data-icon="line-md:arrow-left"></span> Consultar Cursos
                </a>
            </div>
        </div>
    </div>
@endsection