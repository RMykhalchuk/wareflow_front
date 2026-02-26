<?php

namespace App\Http\Controllers\Web\Document;

use App\Http\Controllers\Controller;
use App\Models\Entities\Container\ContainerByDocument;
use App\Tables\ContainerInDocument\TableFacade;
use Illuminate\Http\Request;

final class ContainerInDocumentController extends Controller
{
    public function store(Request $request): \Illuminate\Http\Response
    {
        ContainerByDocument::store($request->except('_token'));

        return response('OK');
    }

    public function tableStore(Request $request): \Illuminate\Http\Response
    {
        ContainerByDocument::storeFromTable(json_decode($request->except('_token')['data'], true), $request->document_id);

        return response('OK');
    }

    public function filter(TableFacade $filter)
    {
        return $filter->getFilteredData();
    }
}
