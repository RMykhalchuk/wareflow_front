<?php

namespace App\Http\Controllers\Web\Document\Income;

use App\Helpers\Document\ProgressHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Document\AbstractLeftoverRequest;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Services\Web\Document\IncomeLeftover\IncomeLeftoverInterface;


class IncomeLeftoverController extends Controller
{
    public function __construct(private IncomeLeftoverInterface $taskLeftoverService) {}

    public function store(AbstractLeftoverRequest $request, Document $document, string $goodsId)
    {
        $data = $this->taskLeftoverService->store($request, $document, $goodsId);

        return response()->json(
            [
                'message' => 'Leftover added to document JSON',
                'data' => $data,
            ], 201);
    }

    public function filter(Document $document, string $goodsId)
    {
        return response()->json($this->taskLeftoverService->getAllByDocumentAndUnique($document, $goodsId));
    }

    public function update(AbstractLeftoverRequest $request, IncomeDocumentLeftover $incomeDocumentLeftover)
    {
        $data = $this->taskLeftoverService->update($request, $incomeDocumentLeftover);

        return response()->json(
            [
                'message' => 'Leftover updated in document JSON',
                'data' => $data,
            ]);
    }

    public function destroy(IncomeDocumentLeftover $incomeDocumentLeftover)
    {
        $data = $this->taskLeftoverService->destroy($incomeDocumentLeftover);

        return response()->json(
            [
                'message' => 'Leftover removed from document JSON',
                'data' => $data,
            ]);
    }

    public function progress(Document $document)
    {
        $documentProducts = $document->data()['sku_table'];
        $progressData = [];
        foreach ($documentProducts as $documentProduct) {
            $progressData[$documentProduct['id']] = ProgressHelper::getIncomeProgress($document->id, $documentProduct['id']);
        }

        return response()->json(
            [
                'data' => $progressData,
            ]);
    }
}

