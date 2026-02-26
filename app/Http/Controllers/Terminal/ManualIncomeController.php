<?php

namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;

use App\Http\Requests\Terminal\RevertIncome\ClosePositionRequest;
use App\Http\Resources\Terminal\Income\Manual\ProductListResource;
use App\Http\Resources\Terminal\Income\Manual\ProductViewResource as ProductViewResourceAlias;
use App\Http\Resources\Terminal\Income\ProductViewResource;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Models\Entities\Goods\Goods;
use App\Services\Terminal\Task\ManualIncome\ManualIncomeServiceInterface;


class ManualIncomeController extends Controller
{
    public function __construct(private ManualIncomeServiceInterface $service) {}

    /**
     * Close position. If position first - new task and document creating
     */
    public function closePosition(ClosePositionRequest $request)
    {
        try {
            $result = $this->service->closePosition($request->validated());

            return response()->json(
                [
                    'success' => true,
                    'data' => $result
                ], 201);

        } catch (\Throwable $e) {

            report($e);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to close position.',
                    'error' => $e->getMessage()
                ], 500);
        }
    }

    /**
     * Get products list by document_id
     */
    public function productList(Document $document)
    {
        return ProductListResource::make($document);
    }

    /**
     * Get product info by document and goods
     */

    public function productView(Document $document, Goods $goods)
    {
        return ProductViewResourceAlias::make(['document' => $document, 'goods' => $goods]);
    }


    /**
     * Close task by document id
     */
    public function closeIncome(Document $document)
    {
        try {
            $this->service->closeIncome($document);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Income closed successfully'
                ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to close income.',
                    'error' => $e->getMessage()
                ], 500);
        }
    }

    /**
     * Remove position
     */

    public function revertPosition(IncomeDocumentLeftover $leftover)
    {
        try {
            $this->service->revertPosition($leftover);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Position reverted successfully'
                ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to revert position.',
                    'error' => $e->getMessage()
                ], 500);
        }
    }

    /**
     * Remove progress
     */

    public function revertIncome(Document $document)
    {
        try {
            $this->service->revertIncome($document);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Income reverted successfully'
                ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to revert income.',
                    'error' => $e->getMessage()
                ], 500);
        }
    }
}

