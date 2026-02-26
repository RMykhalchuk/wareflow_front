<?php

namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;
use App\Models\Entities\Barcode;

class PackageController extends Controller
{
    public function getPackageInfo(string $packageId)
    {
        $barcode = Barcode::where('entity_id', $packageId)->first('barcode');
        return response()->json($barcode);
    }
}
