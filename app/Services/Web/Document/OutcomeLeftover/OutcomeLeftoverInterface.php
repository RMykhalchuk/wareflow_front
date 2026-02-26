<?php

namespace App\Services\Web\Document\OutcomeLeftover;

use App\Http\Requests\Web\Document\OutcomeLeftoverRequest;
use App\Http\Requests\Web\Inventory\LeftoverRequest;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\OutcomeDocumentLeftover;
use App\Models\Entities\Leftover\Leftover;

interface OutcomeLeftoverInterface
{

    public function manualProcessing(array $data, Document $document);
    public function store(Document $document, Leftover $leftover, float $quantity,string $packageId): array;

    public function update(OutcomeLeftoverRequest $request, OutcomeDocumentLeftover $outcomeDocumentLeftover);

    public function destroy(OutcomeDocumentLeftover $outcomeDocumentLeftover): bool;

}
