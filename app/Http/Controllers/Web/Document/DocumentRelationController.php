<?php

namespace App\Http\Controllers\Web\Document;

use App\Http\Controllers\Controller;
use App\Models\Entities\Document\DocumentRelation;
use App\Tables\DocumentType\TableFacade;
use Illuminate\Http\Request;

final class DocumentRelationController extends Controller
{
    public function store(Request $request): \Illuminate\Http\Response
    {
        DocumentRelation::storeByArray($request->except('_token'));

        return response('OK');
    }

    public function delete($document_id, $related_id): \Illuminate\Http\Response
    {
        DocumentRelation::where('document_id', $document_id)
            ->where('related_id', $related_id)?->delete();

        return response('OK');
    }

    public function filter(TableFacade $filter)
    {
        return $filter->getFilteredData();
    }
}
