<?php

namespace App\Services\Web\DocumentType;

use App\Http\Requests\Web\Document\DocumentTypeRequest;
use App\Models\Entities\Document\DocumentType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

interface DocumentTypeServiceInterface
{
    public function getDocumentTypes(): \Illuminate\Database\Eloquent\Collection;

    public function prepareCreateData(): array;

    public function storeDocumentType(DocumentTypeRequest $request): Response;

    public function storeDraftDocumentType(DocumentTypeRequest $request): Response;

    public function prepareEditData(DocumentType $documentType): array;

    public function updateDocumentType(DocumentTypeRequest $request, DocumentType $documentType): Response;

    public function deleteDocumentType(DocumentType $documentType): RedirectResponse;

    public function changeDocumentTypeStatus(string $statusKey, DocumentType $documentType): RedirectResponse;

}
