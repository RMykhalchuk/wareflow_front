<?php

namespace App\Services\Web\Leftover;

use App\Models\Entities\Document\Document;
use App\Models\Entities\Leftover\Leftover;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface LeftoverServiceInterface
{
    public function addByDocument(Request $request, Document $document): array;

    public function removeByDocument(Request $request, Document $document): array;

    public function moveByDocument(Request $request, Document $document): array;

    public function calculatePackage(Leftover $leftover);

    public function enrichWithWarehouseIds(Collection $items): Collection;
}
