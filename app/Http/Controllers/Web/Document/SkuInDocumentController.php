<?php

namespace App\Http\Controllers\Web\Document;

use App\Http\Controllers\Controller;
use App\Models\Entities\Goods\GoodsByDocument;
use App\Tables\SkuInDocument\TableFacade;
use Illuminate\Http\Request;

final class SkuInDocumentController extends Controller
{
    public function store(Request $request): \Illuminate\Http\Response
    {
        GoodsByDocument::store($request->except('_token'));

        return response('OK');
    }

    public function tableStore(Request $request): \Illuminate\Http\Response
    {
        GoodsByDocument::storeFromTable(json_decode($request->except('_token')['data'], true), $request->document_id);

        return response('OK');
    }

    public function filter(TableFacade $filter)
    {
        return $filter->getFilteredData();
    }
}
