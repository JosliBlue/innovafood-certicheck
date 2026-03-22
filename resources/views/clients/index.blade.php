@extends('appsita')

@section('title', 'Gestión de Clientes')

@section('content')
    {{-- Header --}}
    <section class="bg-white rounded-2xl p-6 mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-md">
        <div class="text-center sm:text-left">
            <h1 class="text-primary text-2xl font-bold mb-1">Gestión de Clientes</h1>
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

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mt-4 bg-green-100 text-green-800 border border-green-300 rounded-xl px-5 py-3 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Actions toolbar --}}
    <div class="mt-6 flex justify-end">
        <a href="{{ route('clients.create') }}"
            class="bg-primary hover:bg-primary-hover text-white py-2 px-6 rounded-full font-bold transition-colors flex items-center gap-2">
            <span class="iconify w-4 h-4" data-icon="line-md:plus"></span>
            Nuevo cliente
        </a>
    </div>

    {{-- Table --}}
    <section class="bg-white rounded-2xl shadow-md mt-4 overflow-hidden">
        @if ($clients->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <span class="iconify w-12 h-12 mb-3" data-icon="line-md:account-small"></span>
                <p class="text-sm">No hay clientes registrados aún.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-primary/10 text-primary font-bold uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">Cédula</th>
                            <th class="px-5 py-3">Apellidos</th>
                            <th class="px-5 py-3">Nombres</th>
                            <th class="px-5 py-3">Curso</th>
                            <th class="px-5 py-3">Suscripción</th>
                            <th class="px-5 py-3">Vence</th>
                            <th class="px-5 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($clients as $client)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3 font-mono text-gray-600">{{ $client->id_card }}</td>
                                <td class="px-5 py-3">{{ $client->last_names }}</td>
                                <td class="px-5 py-3">{{ $client->first_names }}</td>
                                <td class="px-5 py-3">{{ $client->course_name }}</td>
                                <td class="px-5 py-3">{{ $client->subscription_type }}</td>
                                <td class="px-5 py-3">{{ $client->expires_at->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 flex justify-end gap-2">
                                    <a href="{{ route('clients.edit', $client) }}"
                                        class="bg-primary/10 hover:bg-primary/20 text-primary p-2 rounded-lg transition-colors"
                                        title="Editar">
                                        <span class="iconify w-4 h-4" data-icon="line-md:pencil"></span>
                                    </a>
                                    <form method="POST" action="{{ route('clients.destroy', $client) }}"
                                        onsubmit="return confirm('¿Eliminar a {{ $client->full_name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition-colors"
                                            title="Eliminar">
                                            <span class="iconify w-4 h-4" data-icon="line-md:remove"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection