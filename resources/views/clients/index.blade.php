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
                $expired = $clients->filter(fn($c) => $c->finished_at->copy()->addYear()->isPast())->count();
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
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                {{-- Search Form --}}
                <form action="{{ route('clients.index') }}" method="GET" class="w-full sm:max-w-md relative flex items-center">
                    <span class="iconify absolute left-4 top-1/2 -translate-y-1/2 text-primary/40 text-lg pointer-events-none w-6 h-6" data-icon="line-md:search"></span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por cédula o nombre..." class="w-full pl-11 pr-24 py-3 bg-white border border-gray-100/80 rounded-2xl text-xs font-semibold text-gray-800 placeholder-gray-400 shadow-[0_10px_30px_rgba(0,0,0,0.02)] focus:outline-none focus:ring-4 focus:ring-primary/5 focus:border-primary/40 transition-all">
                    
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1.5">
                        @if(request('search'))
                            <a href="{{ route('clients.index') }}" class="p-1 px-1.5 rounded-xl bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors" title="Limpiar">
                                <span class="iconify text-sm w-6 h-6" data-icon="line-md:close"></span>
                            </a>
                        @endif
                        <button type="submit" class="bg-primary hover:bg-primary-hover text-white p-2 rounded-xl font-bold shadow-sm shadow-primary/10 hover:shadow transition-all flex items-center justify-center">
                            <span class="iconify text-sm w-6 h-6" data-icon="line-md:search"></span>
                        </button>
                    </div>
                </form>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <a href="{{ route('certificate-templates.index') }}"
                        class="bg-white border border-gray-200 text-primary hover:bg-primary-light py-2.5 px-6 rounded-2xl font-bold shadow-sm transition-all flex items-center gap-2 justify-center text-xs">
                        <span class="iconify text-lg" data-icon="line-md:document-report"></span> Plantillas certificado
                    </a>
                    <a href="{{ route('clients.create') }}"
                        class="bg-primary hover:bg-primary-hover text-white py-2.5 px-6 rounded-2xl font-bold shadow-md shadow-primary/10 hover:shadow-lg hover:shadow-primary/20 transition-all flex items-center gap-2 justify-center">
                        <span class="iconify text-lg" data-icon="line-md:plus"></span> Nuevo cliente
                    </a>
                </div>
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
                                    <th class="px-6 py-4 text-[11px] font-extrabold text-primary/60 uppercase tracking-widest">Estado</th>
                                    <th class="px-6 py-4 text-[11px] font-extrabold text-primary/60 uppercase tracking-widest text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 border-t border-gray-100/60">
                                @foreach ($clients as $client)
                                    @php
                                        $isExpired = $client->finished_at->copy()->addYear()->isPast();
                                        $styles = $isExpired ? [
                                            'bg' => 'bg-orange-500 shadow-orange-100',
                                            'text' => 'text-orange-600',
                                            'label' => 'Retomar Certificación'
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
                                            <span class="inline-flex text-[11px] font-black uppercase tracking-wider {{ $styles['text'] }}">
                                                {{ $styles['label'] }}
                                            </span>
                                            <p class="text-[10px] font-bold text-gray-400 font-mono mt-0.5">{{ $client->finished_at->format('d/m/Y') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2 flex-wrap">
                                                @if ($certificateTemplates->isEmpty())
                                                    <button type="button" disabled
                                                        class="p-2 rounded-xl bg-gray-100 text-gray-300 cursor-not-allowed shadow-sm"
                                                        title="Sube al menos una plantilla en Plantillas certificado">
                                                        <span class="iconify text-lg" data-icon="line-md:document-report"></span>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        class="js-open-certificate p-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white shadow-sm transition-all"
                                                        title="Generar certificado PDF"
                                                        data-client-id="{{ $client->id }}"
                                                        data-client-name="{{ $client->full_name }}">
                                                        <span class="iconify text-lg" data-icon="line-md:document-report"></span>
                                                    </button>
                                                @endif
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

    <dialog id="certificate-dialog"
        class="w-[calc(100%-2rem)] max-w-md rounded-3xl border border-gray-100 bg-white p-0 shadow-2xl backdrop:bg-black/50">
        <form id="certificate-form" method="POST" action="" class="p-6 flex flex-col gap-4">
            @csrf
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-black text-gray-900 leading-tight">Certificado PDF</h2>
                    <p id="certificate-dialog-subtitle" class="text-[11px] font-semibold text-gray-500 mt-1"></p>
                </div>
                <button type="button" id="certificate-dialog-close"
                    class="p-2 rounded-xl bg-gray-50 text-gray-500 hover:bg-gray-100 shrink-0" aria-label="Cerrar">
                    <span class="iconify text-lg" data-icon="line-md:close"></span>
                </button>
            </div>
            <div>
                <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-widest mb-1.5">Plantilla</label>
                <select name="certificate_template_id" required
                    class="w-full px-4 py-3 rounded-2xl border border-gray-100 text-xs font-semibold bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary/40">
                    @foreach ($certificateTemplates as $tpl)
                        <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="certificate-dialog-cancel"
                    class="py-2.5 px-4 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="bg-primary hover:bg-primary-hover text-white py-2.5 px-5 rounded-xl text-xs font-black shadow-md shadow-primary/10 transition-all flex items-center gap-2">
                    <span class="iconify" data-icon="line-md:download-loop"></span>
                    Generar y descargar
                </button>
            </div>
        </form>
    </dialog>

    <script>
        (function () {
            const dialog = document.getElementById('certificate-dialog');
            const form = document.getElementById('certificate-form');
            const subtitle = document.getElementById('certificate-dialog-subtitle');
            const closeBtn = document.getElementById('certificate-dialog-close');
            const cancelBtn = document.getElementById('certificate-dialog-cancel');
            const baseUrl = @json(url('/clients'));

            function closeDialog() {
                dialog.close();
            }

            closeBtn.addEventListener('click', closeDialog);
            cancelBtn.addEventListener('click', closeDialog);

            dialog.addEventListener('cancel', function (e) {
                e.preventDefault();
                closeDialog();
            });

            document.querySelectorAll('.js-open-certificate').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const id = btn.getAttribute('data-client-id');
                    const name = btn.getAttribute('data-client-name') || '';
                    form.action = baseUrl + '/' + encodeURIComponent(id) + '/certificate/pdf';
                    subtitle.textContent = name ? name : '';
                    dialog.showModal();
                });
            });
        })();
    </script>
@endsection