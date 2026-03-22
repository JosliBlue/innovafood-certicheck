@extends('appsita')

@section('title', 'Clientes')

@section('content')
    <div class="min-h-screen bg-primary/10 flex flex-col font-sans relative">

        {{-- Hero Header --}}
        <header class="relative bg-primary-dark pt-10 pb-20 px-6">
            {{-- Decoraciones --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 opacity-5"
                    style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
            </div>

            {{-- Top Navbar Admin --}}
            <div
                class="relative max-w-6xl mx-auto flex justify-between items-center mb-10 z-10 border-b border-white/5 pb-4">
                <div class="flex items-center gap-2">
                    <div
                        class="w-10 h-10 aspect-square bg-white rounded-xl flex items-center justify-center overflow-hidden">
                        <img src="/InnovaFood_Logo.png" class="size-full object-contain p-1">
                    </div>
                    <span class="text-white text-md font-black tracking-tight">{{ config('app.name') }} <span
                            class="text-[10px] text-white/40 uppercase font-black ml-1">Admin</span></span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-white/10 hover:bg-white/20 text-white py-2 px-4 rounded-xl font-bold text-xs transition-colors flex items-center gap-1.5 border border-white/10">
                        <span class="iconify" data-icon="line-md:logout"></span> Salir
                    </button>
                </form>
            </div>

            {{-- Title --}}
            <div class="relative max-w-6xl mx-auto text-center z-10 mb-8">
                <h1 class="text-white text-3xl font-black">Directorio de <span class="text-white/40">Clientes</span></h1>
            </div>

            {{-- Stats Floating Section (Replicating search absolute bottom frame) --}}
            @php
                $total = $clients->count();
                $expired = $clients->filter(fn($c) => $c->expires_at->isPast())->count();
                $active = $total - $expired;
            @endphp
            <div class="absolute left-1/2 -bottom-10 -translate-x-1/2 w-full max-w-xl px-4 z-20">
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xl flex flex-col items-center">
                        <span
                            class="text-[9px] font-extrabold text-primary/60 uppercase tracking-widest text-center">Total</span>
                        <p class="text-xl font-black text-gray-900 mt-0.5 leading-none">{{ $total }}</p>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xl flex flex-col items-center">
                        <span
                            class="text-[9px] font-extrabold text-green-600 uppercase tracking-widest text-center">Vigentes</span>
                        <p class="text-xl font-black text-green-600 mt-0.5 leading-none">{{ $active }}</p>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xl flex flex-col items-center">
                        <span
                            class="text-[9px] font-extrabold text-orange-600 uppercase tracking-widest text-center">Vence</span>
                        <p class="text-xl font-black text-orange-600 mt-0.5 leading-none">{{ $expired }}</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 max-w-6xl w-full mx-auto px-6 pt-16 pb-12 z-10">
            {{-- Toolbar Actions --}}
            <div class="flex justify-end mb-6">
                <a href="{{ route('clients.create') }}"
                    class="bg-primary hover:bg-primary-hover text-white py-2.5 px-6 rounded-2xl font-bold shadow-md shadow-primary/10 hover:shadow-lg hover:shadow-primary/20 transition-all flex items-center gap-2">
                    <span class="iconify text-lg" data-icon="line-md:plus"></span> Nuevo cliente
                </a>
            </div>

            @if ($clients->isEmpty())
                <section
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 flex flex-col items-center justify-center text-gray-400">
                    <span class="iconify w-12 h-12 mb-3 text-primary/20" data-icon="line-md:account-small"></span>
                    <p class="text-sm font-bold text-gray-500">No hay clientes registrados aún.</p>
                </section>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($clients as $client)
                        @php
                            $isExpired = $client->expires_at->isPast();
                            $styles = $isExpired ? [
                                'bg' => 'bg-orange-500 shadow-orange-100',
                                'icon' => 'line-md:alert-circle',
                                'text' => 'text-orange-600',
                                'label' => 'Caducado'
                            ] : [
                                'bg' => 'bg-green-500 shadow-green-100',
                                'icon' => 'line-md:check-all',
                                'text' => 'text-green-600',
                                'label' => 'Vigente'
                            ];
                        @endphp

                        <div
                            class="bg-white rounded-3xl border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.05)] hover:border-white transition-all duration-300 flex flex-col overflow-hidden group">
                            {{-- Card Header --}}
                            <div class="p-6 flex-1">
                                <div class="flex items-center gap-4 mb-5 pb-4 border-b border-gray-100/60">
                                    <div
                                        class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 {{ $styles['bg'] }} text-white shadow-sm">
                                        <span class="iconify text-xl" data-icon="line-md:account"></span>
                                    </div>
                                    <div>
                                        <h4
                                            class="font-black text-gray-900 text-sm md:text-base leading-snug group-hover:text-primary transition-colors">
                                            {{ $client->full_name }}
                                        </h4>
                                        <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mt-0.5">C.I.
                                            {{ $client->id_card }}</p>
                                    </div>
                                </div>

                                {{-- Card Details Grid --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span
                                            class="text-[10px] font-extrabold text-primary/60 uppercase tracking-widest block mb-1">Curso</span>
                                        <p class="text-xs font-extrabold text-gray-800 leading-snug">{{ $client->course_name }}</p>
                                    </div>
                                    <div>
                                        <span
                                            class="text-[10px] font-extrabold text-primary/60 uppercase tracking-widest block mb-1">Suscripción</span>
                                        <p class="text-xs font-bold text-gray-500 capitalize">{{ $client->subscription_type }}</p>
                                    </div>
                                    <div>
                                        <span
                                            class="text-[10px] font-extrabold text-primary/60 uppercase tracking-widest block mb-1">Estado</span>
                                        <span
                                            class="inline-flex text-[11px] font-black uppercase tracking-wider {{ $styles['text'] }}">
                                            {{ $styles['label'] }}
                                        </span>
                                    </div>
                                    <div>
                                        <span
                                            class="text-[10px] font-extrabold text-primary/60 uppercase tracking-widest block mb-1">Vence</span>
                                        <time
                                            class="text-xs font-black text-gray-800 font-mono">{{ $client->expires_at->format('d/m/Y') }}</time>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Footer Actions --}}
                            <div class="border-t border-gray-100/60 flex divide-x divide-gray-100/60">
                                <a href="{{ route('clients.edit', $client) }}"
                                    class="flex-1 flex items-center justify-center gap-2 py-5 bg-primary-light/30 hover:bg-primary-light text-primary/60 hover:text-primary font-black text-xs uppercase tracking-wider transition-colors"
                                    title="Editar">
                                    <span class="iconify text-base w-7 h-7" data-icon="line-md:pencil"></span>
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('clients.destroy', $client) }}"
                                    onsubmit="return confirm('¿Eliminar a {{ $client->full_name }}?')" class="flex-1 flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 py-5 bg-red-50/20 hover:bg-red-50 text-red-500 hover:text-red-600 font-black text-xs uppercase tracking-wider transition-colors"
                                        title="Eliminar">
                                        <span class="iconify text-base w-7 h-7" data-icon="line-md:remove"></span>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
@endsection