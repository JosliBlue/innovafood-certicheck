<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCertificateTemplateRequest;
use App\Http\Requests\UpdateCertificateTemplateFieldsRequest;
use App\Models\CertificateTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class CertificateTemplateController extends Controller
{
    /**
     * @return array{background_mime: string, background_base64: string}
     */
    private function encodeBackgroundFile(UploadedFile $file): array
    {
        return [
            'background_mime' => $file->getMimeType() ?: 'application/octet-stream',
            'background_base64' => base64_encode($file->getContent()),
        ];
    }

    /**
     * @return array{background_back_mime: string, background_back_base64: string}
     */
    private function encodeBackgroundBackFile(UploadedFile $file): array
    {
        return [
            'background_back_mime' => $file->getMimeType() ?: 'application/octet-stream',
            'background_back_base64' => base64_encode($file->getContent()),
        ];
    }

    public function index(): View
    {
        $templates = CertificateTemplate::query()->orderBy('name')->get();

        return view('certificate_templates.index', compact('templates'));
    }

    public function store(StoreCertificateTemplateRequest $request): RedirectResponse
    {
        $file = $request->file('background');
        $payload = [
            'name' => $request->validated('name'),
            ...$this->encodeBackgroundFile($file),
            'fields' => CertificateTemplate::defaultFieldsKeyed(),
        ];

        $backFile = $request->file('background_back');
        if ($backFile instanceof UploadedFile) {
            $payload = array_merge($payload, $this->encodeBackgroundBackFile($backFile));
        }

        $template = CertificateTemplate::query()->create($payload);

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
        $bgBackUrl = $certificate_template->backgroundBackDataUri() ?? '';

        /** @var array<string, array{label: string, css_family: string, faces: array<int, array<string, mixed>>}> $certificateFontFamilies */
        $certificateFontFamilies = config('certificate_fonts.families', []);

        return view('certificate_templates.editor', [
            'template' => $certificate_template,
            'editorRows' => $editorRows,
            'bgUrl' => $bgUrl,
            'bgBackUrl' => $bgBackUrl,
            'certificateFontFamilies' => $certificateFontFamilies,
        ]);
    }

    public function update(UpdateCertificateTemplateFieldsRequest $request, CertificateTemplate $certificate_template): RedirectResponse
    {
        $validated = $request->validated();

        $update = [
            'name' => $validated['name'],
            'fields' => $validated['fields'],
        ];

        if ($request->boolean('remove_background_back')) {
            $update['background_back_mime'] = null;
            $update['background_back_base64'] = null;
        }

        $backFile = $request->file('background_back');
        if ($backFile instanceof UploadedFile) {
            $update = array_merge($update, $this->encodeBackgroundBackFile($backFile));
        }

        $certificate_template->update($update);

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
