<?php

namespace App\Http\Controllers\Web\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Document\DoctypeFieldRequest;
use App\Models\Entities\Document\DoctypeField;

final class DoctypeFieldController extends Controller
{
    public function store(DoctypeFieldRequest $request): \Illuminate\Http\Response
    {
        DoctypeField::create($request->all());

        return response('OK');
    }


    public function destroy($key): \Illuminate\Http\Response
    {
        DoctypeField::where('key', $key)->first()->delete();

        return response('OK');
    }
}
