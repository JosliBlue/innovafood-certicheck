@extends('appsita')

@section('title', 'Nuevo Cliente')

@section('content')
    {{-- Header --}}
    <section class="bg-white rounded-2xl p-6 mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-md">
        <div>
            <h1 class="text-primary text-2xl font-bold mb-1">Nuevo Cliente</h1>
            <p class="text-gray-500 text-sm">Completa los campos para registrar un cliente.</p>
        </div>
        <a href="{{ route('clients.index') }}"
            class="bg-primary/10 hover:bg-primary/20 text-primary py-2 px-5 rounded-full font-bold transition-colors flex items-center gap-2">
            <span class="iconify w-4 h-4" data-icon="line-md:arrow-left"></span>
            Volver
        </a>
    </section>

    <section class="bg-white rounded-2xl shadow-md mt-4 p-6">
        <form method="POST" action="{{ route('clients.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @csrf

            @include('clients._form')

            <div class="sm:col-span-2 flex justify-end">
                <button type="submit"
                    class="bg-primary hover:bg-primary-hover text-white font-bold py-2 px-8 rounded-full transition-colors">
                    Registrar cliente
                </button>
            </div>
        </form>
    </section>
@endsection
