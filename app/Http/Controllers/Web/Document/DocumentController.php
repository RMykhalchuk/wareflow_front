<?php

namespace App\Http\Controllers\Web\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Document\DocumentRequest;
use App\Http\Requests\Web\Document\OutcomeDocumentFreeSelectionRequest;
use App\Enums\Documents\DocumentKind;
use App\Models\Dictionaries\DocumentStatus;
use App\Models\Entities\Document\DoctypeStructure;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\DocumentType;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Models\Entities\Document\OutcomeDocumentLeftover;
use App\Services\Web\Document\DocumentServiceInterface;
use Illuminate\Support\Facades\Cache;
use App\Tables\Document\Income\Containers\TableFacade as IncomeContainersTableFacade;
use App\Tables\Document\Outcome\Containers\TableFacade as OutcomeContainersTableFacade;
use App\Tables\Document\TableFacade;
use App\Tables\DocumentTask\TableFacade as TaskTableFacade;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentServiceInterface $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index(Request $request): View
    {
        $documentTypes = $this->documentService->getDocumentTypes($request);
        $isAdmin = Auth::user()->isAdmin();
        return view('documents.list', compact('documentTypes', 'isAdmin'));
    }

    public function table(Request $request, DocumentType $documentType): View
    {
        $warehouseId = Auth::user()->currentWarehouseId;
        $documentsCount = $this->documentService->getDocumentsCount($documentType,$warehouseId);
        $documentStructure = DoctypeStructure::where('kind', $documentType->kind)->first();

        return view('documents.index', compact('documentsCount', 'documentType', 'documentStructure'));
    }

    public function create(DocumentType $documentType): View
    {
        return view('documents.create', $this->documentService->prepareCreateData($documentType));
    }

    public function store(DocumentRequest $request): JsonResponse
    {
        $documentId = $this->documentService->storeDocument($request);

        return response()->json(['document_id' => $documentId]);
    }

    public function show(Document $document): View
    {
        $data = $this->documentService->prepareShowData($document);
        return view('documents.view', $data);
    }

    public function edit(Document $document): View
    {
        return view('documents.update', $this->documentService->prepareEditData($document));
    }

    public function update(Request $request, Document $document): JsonResponse
    {
        $document_id = $this->documentService->updateDocument($request, $document);
        return response()->json(['document_id' => $document_id]);
    }

    public function destroy(Document $document): Response
    {
        $this->documentService->deleteDocument($document);
        return response('Deleted');
    }

    public function filter()
    {
        return TableFacade::getFilteredData();
    }

    public function createRelatedDocument(Request $request): Response
    {
        $this->documentService->storeRelatedDocument($request);
        return response('OK');
    }

    public function updateFreeSelection(OutcomeDocumentFreeSelectionRequest $request, Document $document): JsonResponse
    {
        $data = $this->documentService->updateFreeSelection($request, $document);

        return response()->json(
            [
                'message' => 'Free selection updated successfully',
                'data' => $data,
            ]);
    }

    public function taskFilter($documentId)
    {
        return TaskTableFacade::getFilteredData($documentId);
    }

    public function containerFilter(Document $document)
    {
        return IncomeContainersTableFacade::getFilteredData($document);
    }

    public function outcomeContainerFilter(Document $document)
    {
        return OutcomeContainersTableFacade::getFilteredData($document);
    }

    public function setState(Request $request, Document $document)
    {
        $state = $request->state;
        $data = ['state' => $state];

        if ($state === 'manual') {
            $data['status_id'] = Cache::remember('document_status_process_id', 3600, function () {
                return DocumentStatus::where('key', 'process')->value('id');
            });
        } elseif ($state === 'task') {
            $data['status_id'] = Cache::remember('document_status_created_id', 3600, function () {
                return DocumentStatus::where('key', 'created')->value('id');
            });

            $kind = $document->documentType->kind;

            if ($kind === DocumentKind::ARRIVAL->value) {
                IncomeDocumentLeftover::where('document_id', $document->id)->delete();
            } elseif ($kind === DocumentKind::OUTCOME->value) {
                OutcomeDocumentLeftover::where('document_id', $document->id)->delete();
            }
        }

        $document->update($data);

        return response('OK');
    }
}
