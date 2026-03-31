@extends('appsita')

@section('title', 'Editar Cliente')

@section('content')
    <div class="min-h-screen bg-[#faf8f7] flex flex-col font-sans relative">

        {{-- Hero Header --}}
        <header class="relative bg-primary-dark pt-10 pb-24 px-6">
            {{-- Decoraciones --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
            </div>

            {{-- Navbar --}}
            <div class="relative max-w-2xl mx-auto flex justify-between items-center mb-10 z-10">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 aspect-square bg-white rounded-xl flex items-center justify-center overflow-hidden">
                        <img src="/InnovaFood_Logo.png" class="size-full object-contain p-1">
                    </div>
                    <span class="text-white text-md font-black tracking-tight">{{ config('app.name') }}</span>
                </div>
                <a href="{{ route('clients.index') }}" class="bg-white/10 hover:bg-white/20 text-white py-2 px-4 rounded-xl font-bold text-xs transition-colors flex items-center gap-1.5 border border-white/10">
                    <span class="iconify" data-icon="line-md:arrow-left"></span> Volver
                </a>
            </div>

            {{-- Title --}}
            <div class="relative max-w-2xl mx-auto text-center z-10">
                <h1 class="text-white text-3xl font-black">Editar <span class="text-white/40">Cliente</span></h1>
                <p class="text-white/60 text-xs mt-1 font-bold">{{ $client->full_name }}</p>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 max-w-2xl w-full mx-auto px-6 -mt-12 pb-12 z-20">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_20px_60px_rgba(0,0,0,0.04)] p-8">
                <form method="POST" action="{{ route('clients.update', $client) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @csrf
                    @method('PUT')

                    @include('clients._form')

                    <div class="sm:col-span-2 flex justify-end mt-4">
                        <button type="submit" class="bg-primary hover:bg-primary-hover text-white font-black py-3 px-8 rounded-2xl shadow-md shadow-primary/10 hover:shadow-lg hover:shadow-primary/20 transition-all flex items-center gap-2">
                            <span class="iconify text-lg" data-icon="line-md:check-all"></span> Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection
