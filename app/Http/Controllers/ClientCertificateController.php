<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use App\Models\Client;
use App\Services\CertificatePdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class ClientCertificateController extends Controller
{
    public function __construct(
        private readonly CertificatePdfService $certificatePdfService,
    ) {}

    public function download(Client $client): Response|RedirectResponse
    {
        $template = CertificateTemplate::findForCourseName($client->course_name);

        if ($template === null) {
            return redirect()->route('clients.index')
                ->with('error', "No hay plantilla de certificado para el curso «{$client->course_name}». Crea una plantilla con el mismo nombre del curso.");
        }

        $client->update(['certificate_printed' => true]);

        return $this->certificatePdfService->download($client, $template);
    }
}
