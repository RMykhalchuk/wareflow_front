<?php

namespace App\Services\Terminal\Task\Income;

use App\Http\Requests\Terminal\Income\ClosePositionRequest;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Task\Task;
use Illuminate\Http\Request;

interface IncomeTaskInterface
{
    public function getLatest(Request $request);

    public function storeItems(ClosePositionRequest $request, Document $document, string $goodsId) : array;

    public function getProductViewData(Task $task, string $productId): array;
}
