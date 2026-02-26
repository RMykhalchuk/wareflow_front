<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SKU\BarcodeRequest;
use App\Models\Entities\Barcode;


final class BarcodeController extends Controller
{
    public function create(BarcodeRequest $request): \Illuminate\Http\RedirectResponse
    {
        Barcode::create($request->all());

        return redirect()->back();
    }

    public function update(BarcodeRequest $request, Barcode $barcode): \Illuminate\Http\RedirectResponse
    {
        $barcode->update($request->all());

        return redirect()->back();
    }

    public function delete(Barcode $barcode): \Illuminate\Http\RedirectResponse
    {
        $barcode->delete();

        return redirect()->back();
    }
}
