<?php

namespace App\Http\Controllers\Web\Document\Outcome;

use App\Helpers\Document\ProgressHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Document\OutcomeLeftoverRequest;
use App\Http\Requests\Web\Inventory\LeftoverRequest;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\OutcomeDocumentLeftover;
use App\Models\Entities\Leftover\Leftover;
use App\Services\Web\Document\OutcomeLeftover\OutcomeLeftoverInterface;
use App\Services\Web\Document\ReserveLeftover\ReserveLeftoverInterface;
use App\Tables\LeftoverByGoods\TableFacade;


class OutcomeLeftoverController extends Controller
{
    public function __construct(private OutcomeLeftoverInterface $outcomeLeftoverService, private ReserveLeftoverInterface $reserveLeftoverService) {}

    public function store(LeftoverRequest $request, Document $document)
    {
        $data = $this->outcomeLeftoverService->manualProcessing(
            $request->validated(),
            $document
        );

        return response()->json(
            [
                'message' => 'Leftover added to document JSON',
                'data' => $data,
            ], 201);
    }

    public function filter(Document $document, string $goodsId)
    {
        $warehouseId = $document->warehouse_id;
        return TableFacade::getFilteredData($warehouseId, $goodsId);
    }

    public function update(OutcomeLeftoverRequest $request, OutcomeDocumentLeftover $outcomeDocumentLeftover)
    {
        $data = $this->outcomeLeftoverService->update($request, $outcomeDocumentLeftover);

        return response()->json(
            [
                'message' => 'Leftover updated in document JSON',
                'data' => $data,
            ]);
    }

    public function destroy(OutcomeDocumentLeftover $outcomeDocumentLeftover)
    {
        $data = $this->outcomeLeftoverService->destroy($outcomeDocumentLeftover);

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
            $progressData[$documentProduct['id']] = ProgressHelper::getOutcomeProgress($document->id, $documentProduct['id']);
        }

        return response()->json(
            [
                'data' => $progressData,
            ]);
    }

    public function reserve(Document $document)
    {
        $this->reserveLeftoverService->reserve($document);

        return response('Success');
    }

    public function removeReservation(Document $document)
    {
        $this->reserveLeftoverService->removeReservation($document);

        return response('Success');
    }
}

