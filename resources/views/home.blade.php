@extends('appsita')

@section('title', 'Verificar certificado')

@section('content')
    <section class="bg-white rounded-2xl p-6 mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-md">
        <div class="text-center sm:text-left">
            <h1 class="text-primary text-2xl font-bold mb-1">Panel de Control</h1>
            <p class="text-gray-500">Bienvenido, <strong class="text-primary">{{ auth()->user()->email }}</strong>.</p>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit"
                class="w-full sm:w-auto bg-primary/10 hover:bg-primary/20 text-primary py-2 px-6 rounded-full font-bold transition-colors flex items-center justify-center gap-2">
                <span class="iconify w-4 h-4" data-icon="line-md:logout"></span>
                Cerrar sesión
            </button>
        </form>
    </section>
@endsection