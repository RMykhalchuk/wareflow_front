<?php

namespace App\Http\Controllers\Web\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Document\DocumentTypeRequest;
use App\Models\Entities\Document\DocumentType;
use App\Services\Web\DocumentType\DocumentTypeServiceInterface;
use App\Tables\DocumentTypeInDocumentTable\TableFacade;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class DocumentTypeController extends Controller
{
    protected $documentTypeService;

    public function __construct(DocumentTypeServiceInterface $documentTypeService)
    {
        $this->documentTypeService = $documentTypeService;
    }

    public function index(): View
    {
        $documentTypes = $this->documentTypeService->getDocumentTypes();
        $isAdmin = Auth::user()->isAdmin();
        return view('document-types.index', compact('documentTypes', 'isAdmin'));
    }

    public function create(): View
    {
        return view('document-types.create', $this->documentTypeService->prepareCreateData());
    }

    public function store(DocumentTypeRequest $request): Response
    {
        return $this->documentTypeService->storeDocumentType($request);
    }

    public function storeDraft(DocumentTypeRequest $request): Response
    {
        return $this->documentTypeService->storeDraftDocumentType($request);
    }

    public function edit(DocumentType $documentType): View
    {
        return view('document-types.edit', $this->documentTypeService->prepareEditData($documentType));
    }

    public function update(DocumentTypeRequest $request, DocumentType $documentType): Response
    {
        return $this->documentTypeService->updateDocumentType($request, $documentType);
    }

    public function destroy(DocumentType $documentType): RedirectResponse
    {
        return $this->documentTypeService->deleteDocumentType($documentType);
    }

    public function changeStatus(string $statusKey, DocumentType $documentType): RedirectResponse
    {
        return $this->documentTypeService->changeDocumentTypeStatus($statusKey, $documentType);
    }

    public function preview(): \Illuminate\Contracts\View\View
    {

        return view('document-types.preview');
    }

    public function filter(TableFacade $filter)
    {
        return $filter->getFilteredData();
    }
}
