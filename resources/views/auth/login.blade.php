@extends('appsita')

@section('title', 'Iniciar sesión')

@php $inputClass = 'w-full p-3 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-shadow'; @endphp

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-2xl p-8 shadow-md">
        <h1 class="text-primary text-center text-3xl mb-2 font-bold mb-6">
            {{ env('APP_NAME') }}<span class="text-sm border ml-1 border-primary px-2 py-0.5 rounded-full align-middle">G.C</span>
        </h1>

        @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
            <div class="bg-red-50 text-red-700 p-4 mb-6 rounded text-sm">
                Algo salió mal. Por favor intenta de nuevo.
            </div>
        @endif

        <form method="POST" action="{{ route('login.try') }}" class="flex flex-col gap-5">
            @csrf

            <div>
                <label for="email" class="block mb-1.5 font-semibold text-primary">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="{{ $inputClass }}">
                @error('email')
                    <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block mb-1.5 font-semibold text-primary">Contraseña</label>
                <input id="password" type="password" name="password" required class="{{ $inputClass }}">
                @error('password')
                    <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="mt-2 w-full bg-primary hover:bg-primary-hover text-white py-3 rounded-full font-bold transition-colors">
                Iniciar sesión
            </button>
        </form>
    </div>
@endsection