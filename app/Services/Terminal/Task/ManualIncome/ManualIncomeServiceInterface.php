<?php

namespace App\Services\Terminal\Task\ManualIncome;

use App\Http\Requests\Terminal\Income\ClosePositionRequest;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\IncomeDocumentLeftover;

interface ManualIncomeServiceInterface
{
    public function closePosition(array $data): array;

    public function closeIncome(Document $document): void;

    public function revertPosition(IncomeDocumentLeftover $incomeDocumentLeftover): void;

    public function revertIncome(Document $document): void;
}
