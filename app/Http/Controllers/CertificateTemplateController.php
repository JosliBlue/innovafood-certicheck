<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCertificateTemplateRequest;
use App\Http\Requests\UpdateCertificateTemplateFieldsRequest;
use App\Models\CertificateTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class CertificateTemplateController extends Controller
{
    public function index(): View
    {
        $templates = CertificateTemplate::query()->orderBy('name')->get();

        return view('certificate_templates.index', compact('templates'));
    }

    public function store(StoreCertificateTemplateRequest $request): RedirectResponse
    {
        $file = $request->file('background');
        $mime = $file->getMimeType() ?: 'application/octet-stream';
        $binary = $file->getContent();

        $template = CertificateTemplate::query()->create([
            'name' => $request->validated('name'),
            'background_mime' => $mime,
            'background_base64' => base64_encode($binary),
            'fields' => CertificateTemplate::defaultFieldsKeyed(),
        ]);

        return redirect()->route('certificate-templates.edit', $template)
            ->with('success', 'Plantilla creada. Arrastra las cajas o usa los números del panel para colocar cada dato.');
    }

    public function edit(CertificateTemplate $certificate_template): View
    {
        $merged = $certificate_template->mergedFieldsKeyed();
        $editorRows = [];
        foreach (CertificateTemplate::FIELD_KEYS as $key) {
            $editorRows[] = array_merge($merged[$key], [
                'label' => CertificateTemplate::FIELD_LABELS[$key],
            ]);
        }

        $bgUrl = $certificate_template->backgroundDataUri() ?? '';

        return view('certificate_templates.editor', [
            'template' => $certificate_template,
            'editorRows' => $editorRows,
            'bgUrl' => $bgUrl,
        ]);
    }

    public function update(UpdateCertificateTemplateFieldsRequest $request, CertificateTemplate $certificate_template): RedirectResponse
    {
        $validated = $request->validated();

        $certificate_template->update([
            'name' => $validated['name'],
            'fields' => $validated['fields'],
        ]);

        return redirect()->route('certificate-templates.edit', $certificate_template)
            ->with('success', 'Diseño guardado correctamente.');
    }

    public function destroy(CertificateTemplate $certificate_template): RedirectResponse
    {
        $certificate_template->delete();

        return redirect()->route('certificate-templates.index')
            ->with('success', 'Plantilla eliminada.');
    }
}
