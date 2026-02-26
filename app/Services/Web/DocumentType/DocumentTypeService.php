<?php

namespace App\Services\Web\DocumentType;

use App\Http\Requests\Web\Document\DocumentTypeRequest;
use App\Models\Document\DoctypeStatus;
use App\Models\Entities\Document\DoctypeField;
use App\Models\Entities\Document\DocumentType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DocumentTypeService implements DocumentTypeServiceInterface
{
    public function getDocumentTypes(): \Illuminate\Database\Eloquent\Collection
    {
        return DocumentType::with('status')
            ->get(['id', 'name', 'settings']);
    }

    public function prepareCreateData(): array
    {
        return [
            'doctypeFields' => DoctypeField::all(),
            'docTypes' => DocumentType::get(['id', 'name']),
            'isAdmin' => Auth::user()->isAdmin(),
        ];
    }

    public function storeDocumentType(DocumentTypeRequest $request): Response
    {
        DocumentType::create($request->validated());
        return response('OK');
    }

    public function storeDraftDocumentType(DocumentTypeRequest $request): Response
    {
        $data = $request->validated();
        DocumentType::create($data);
        return response('OK');
    }

    public function prepareEditData(DocumentType $documentType): array
    {
        return [
            'documentType' => $documentType,
            'doctypeFields' => DoctypeField::all(),
            'docTypes' => DocumentType::where('id', '!=', $documentType->id)
                ->get(['id', 'name']),
        ];
    }

    public function updateDocumentType(DocumentTypeRequest $request, DocumentType $documentType): Response
    {
        $documentType->update($request->validated());
        return response('OK');
    }

    public function deleteDocumentType(DocumentType $documentType): RedirectResponse
    {
        $documentType->delete();
        return redirect()->back();
    }

    public function changeDocumentTypeStatus(string $statusKey, DocumentType $documentType): RedirectResponse
    {
        $status = $statusKey !== 'null' ? DoctypeStatus::where('key', $statusKey)->first()->id : null;
        $documentType->update(['status_id' => $status]);
        return redirect()->back();
    }
}
