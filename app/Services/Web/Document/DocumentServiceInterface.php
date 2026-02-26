<?php

namespace App\Services\Web\Document;

use App\Http\Requests\Web\Document\DocumentRequest;
use App\Http\Requests\Web\Document\OutcomeDocumentFreeSelectionRequest;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface DocumentServiceInterface
{
    public function getDocumentTypes(Request $request): Collection;

    public function getDocumentsCount(DocumentType $documentType, ?string $warehouseId = null): int;

    public function prepareCreateData(DocumentType $documentType): array;

    public function storeDocument(DocumentRequest $request);

    public function prepareShowData(Document $document): array;

    public function prepareEditData(Document $document): array;

    public function updateDocument(Request $request, Document $document);

    public function deleteDocument(Document $document): void;

    public function storeRelatedDocument(Request $request): void;

    public function updateFreeSelection(OutcomeDocumentFreeSelectionRequest $request, Document $document): array;
}
