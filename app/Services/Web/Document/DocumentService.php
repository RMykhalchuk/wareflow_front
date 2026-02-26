<?php

namespace App\Services\Web\Document;

use App\Helpers\Document\ProgressHelper;
use App\Http\Requests\Web\Document\DocumentRequest;
use App\Http\Requests\Web\Document\OutcomeDocumentFreeSelectionRequest;
use App\Models\Dictionaries\GoodsCategory;
use App\Models\Dictionaries\ServiceCategories;
use App\Models\Entities\Container\ContainerType;
use App\Models\Entities\Document\DoctypeStructure;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\DocumentType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DocumentService implements DocumentServiceInterface
{
    public function getDocumentTypes(Request $request): Collection
    {
        $warehouseId = $request->get('warehouse_id');

        return DocumentType::withCount(
            [
                'documents as documents_count' => function ($query) use ($warehouseId) {
                    $query->where('creator_company_id', User::currentCompany());
                    if ($warehouseId) {
                        $query->where('warehouse_id', $warehouseId);
                    }
                },
            ])->get();
    }

    public function getDocumentsCount(DocumentType $documentType, ?string $warehouseId = null): int
    {
        return Document::query()
            ->where('type_id', $documentType->id)
            ->when($warehouseId, fn($q) => $q->where('warehouse_id', $warehouseId))
            ->count();
    }

    public function prepareCreateData(DocumentType $documentType): array
    {
        return [
            'documentType' => $documentType,
            'documentStructure' => $this->getStructureByType($documentType),
            'categories' => GoodsCategory::all(),
            'containerTypes' => ContainerType::all(),
            'relatedDocumentsArray' => $documentType->getRelatedDocumentsArray(),
            'serviceCategories' => ServiceCategories::all(),
        ];
    }

    public function storeDocument(DocumentRequest $request)
    {
        return Document::store($request);
    }

    public function prepareShowData(Document $document): array
    {
        $document->loadMissing(['status', 'log']);
        $documentType = $document->documentType;

        $documentProducts = $document->data()['sku_table'] ?? [];
        $progressData = $this->getProgressData($document, $documentProducts);



        return [
            'documentType' => $documentType,
            'documentStructure' => $this->getStructureByType($documentType),
            'document' => $document,
            'relatedDocumentsArray' => $documentType->getRelatedDocumentsArray(),
            'progressData' => $progressData,
        ];
    }

    public function prepareEditData(Document $document): array
    {
        $documentType = $document->documentType;

        return [
            'documentType' => $documentType,
            'documentStructure' => $this->getStructureByType($documentType),
            'document' => $document,
            'categories' => GoodsCategory::all(),
            'containerTypes' => ContainerType::all(),
            'relatedDocumentsArray' => $documentType->getRelatedDocumentsArray(),
            'serviceCategories' => ServiceCategories::all(),
        ];
    }

    public function updateDocument(Request $request, Document $document): string
    {
        $document->updateData($request->except(['_token', '_method']));
        return $document->id;
    }

    public function deleteDocument(Document $document): void
    {
        $document->delete();
    }

    public function storeRelatedDocument(Request $request): void
    {
        Document::storeRelated($request->except('_token'));
    }


    public function updateFreeSelection(OutcomeDocumentFreeSelectionRequest $request, Document $document): array
    {
        $data = $document->data();
        $data['free_selection'] = $request->validated('free_selection');

        $document->update(['data' => $data]);

        return $document->data;
    }


    protected function getStructureByType(DocumentType $documentType): ?DoctypeStructure
    {
        return DoctypeStructure::where('kind', $documentType->kind)->first();
    }

    public function getProgressData(Document $document, array $products): array
    {
        $isArrival = $document->documentType->kind === 'arrival';
        $progressData = [];

        foreach ($products as $product) {
            $id = $product['id'] ?? null;
            if (!$id) {
                continue;
            }

            $progressData[$id] = $isArrival
                ? ProgressHelper::getIncomeProgress($document->id, $id)
                : ProgressHelper::getOutcomeProgress($document->id, $id);
        }

        return $progressData;
    }
}
