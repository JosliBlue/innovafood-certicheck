<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use App\Models\Client;
use App\Services\CertificatePdfService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ClientCertificateController extends Controller
{
    public function __construct(
        private readonly CertificatePdfService $certificatePdfService,
    ) {}

    public function download(Request $request, Client $client)
    {
        $validated = $request->validate([
            'certificate_template_id' => ['required', 'exists:certificate_templates,id'],
        ]);

        $template = CertificateTemplate::query()->findOrFail($validated['certificate_template_id']);

        return $this->certificatePdfService->download($client, $template);
    }
}
