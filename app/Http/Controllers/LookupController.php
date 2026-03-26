<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class LookupController extends Controller
{
    public function index(): View
    {
        return view('lookup.index');
    }

    public function search(Request $request): View
    {
        $request->validate([
            'cedula' => ['required', 'string', 'max:20'],
        ], [
            'cedula.required' => 'Por favor ingresa una cédula.',
        ]);

        $cedula = $request->input('cedula');

        $records = Client::query()
            ->where('id_card', $cedula)
            ->orderBy('expires_at', 'desc')
            ->get();

        $person = $records->first();

        return view('lookup.index', compact('records', 'person', 'cedula'));
    }
}
