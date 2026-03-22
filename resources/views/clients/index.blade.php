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
                <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 flex flex-col items-center justify-center text-gray-400">
                    <span class="iconify w-12 h-12 mb-3 text-primary/20" data-icon="line-md:account-small"></span>
                    <p class="text-sm font-bold text-gray-500">No hay clientes registrados aún.</p>
                </section>
            @else
                <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.02)] overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100/60">
                                    <th class="px-6 py-4 text-[11px] font-extrabold text-primary/60 uppercase tracking-widest">Cliente</th>
                                    <th class="px-6 py-4 text-[11px] font-extrabold text-primary/60 uppercase tracking-widest">Curso</th>
                                    <th class="px-6 py-4 text-[11px] font-extrabold text-primary/60 uppercase tracking-widest">Suscripción</th>
                                    <th class="px-6 py-4 text-[11px] font-extrabold text-primary/60 uppercase tracking-widest">Estado</th>
                                    <th class="px-6 py-4 text-[11px] font-extrabold text-primary/60 uppercase tracking-widest text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 border-t border-gray-100/60">
                                @foreach ($clients as $client)
                                    @php
                                        $isExpired = $client->expires_at->isPast();
                                        $styles = $isExpired ? [
                                            'bg' => 'bg-orange-500 shadow-orange-100',
                                            'text' => 'text-orange-600',
                                            'label' => 'Caducado'
                                        ] : [
                                            'bg' => 'bg-green-500 shadow-green-100',
                                            'text' => 'text-green-600',
                                            'label' => 'Vigente'
                                        ];
                                    @endphp
                                    <tr class="hover:bg-primary-light/10 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-white shadow-sm {{ $styles['bg'] }} shrink-0">
                                                    {{ substr($client->first_names, 0, 1) }}{{ substr($client->last_names, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900 group-hover:text-primary transition-colors leading-snug">{{ $client->full_name }}</p>
                                                    <p class="text-[10px] text-gray-400 font-extrabold tracking-wider mt-0.5">C.I. {{ $client->id_card }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-xs font-bold text-gray-700 leading-snug">{{ $client->course_name }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex text-[10px] font-bold text-gray-500 capitalize bg-gray-100 px-2 py-0.5 rounded-lg">
                                                {{ $client->subscription_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex text-[11px] font-black uppercase tracking-wider {{ $styles['text'] }}">
                                                {{ $styles['label'] }}
                                            </span>
                                            <p class="text-[10px] font-bold text-gray-400 font-mono mt-0.5">{{ $client->expires_at->format('d/m/Y') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('clients.edit', $client) }}" class="p-2 rounded-xl bg-primary/10 text-primary hover:bg-primary hover:text-white shadow-sm transition-all" title="Editar">
                                                    <span class="iconify text-lg" data-icon="line-md:pencil"></span>
                                                </a>
                                                <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('¿Eliminar a {{ $client->full_name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white shadow-sm transition-all" title="Eliminar">
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
                </div>
            @endif
        </main>
    </div>
@endsection