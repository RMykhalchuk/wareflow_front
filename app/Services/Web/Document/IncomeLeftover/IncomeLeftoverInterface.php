<?php

namespace App\Services\Web\Document\IncomeLeftover;

use App\Http\Requests\Web\Document\AbstractLeftoverRequest;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\IncomeDocumentLeftover;

interface IncomeLeftoverInterface
{
    public function store(AbstractLeftoverRequest $request, Document $document, string $goodsId): array;

    public function update(AbstractLeftoverRequest $request, IncomeDocumentLeftover $incomeDocumentLeftover);

    public function destroy(IncomeDocumentLeftover $incomeDocumentLeftover): bool;

    public function getAllByDocumentAndUnique(Document $document, string $uniqueId): array;
}
