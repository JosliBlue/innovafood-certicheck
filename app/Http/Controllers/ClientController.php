<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\CertificateTemplate;
use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class ClientController extends Controller
{
    private function clientsIndexQuery(Request $request): Builder
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id_card', 'like', '%'.$search.'%')
                    ->orWhere('full_name', 'like', '%'.$search.'%');
            });
        }

        return $query;
    }

    public function index(Request $request): View
    {
        $query = $this->clientsIndexQuery($request);

        $total = (clone $query)->count();
        $expired = (clone $query)
            ->where('finished_at', '<', now()->subYear()->toDateString())
            ->count();
        $active = $total - $expired;

        $clients = (clone $query)
            ->orderByDesc('created_at')
            ->orderBy('full_name')
            ->paginate(20)
            ->withQueryString();

        $certificateTemplatesByCourse = CertificateTemplate::query()
            ->orderBy('name')
            ->get()
            ->keyBy(fn (CertificateTemplate $template): string => CertificateTemplate::normalizeCourseName($template->name));

        return view('clients.index', compact(
            'clients',
            'certificateTemplatesByCourse',
            'total',
            'active',
            'expired',
        ));
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        Client::query()->create($request->validated());

        return redirect()->route('clients.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return redirect()->route('clients.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
