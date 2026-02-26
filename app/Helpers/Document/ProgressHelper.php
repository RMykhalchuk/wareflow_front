<?php

namespace App\Helpers\Document;

use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Models\Entities\Document\OutcomeDocumentLeftover;

class ProgressHelper
{
    public static function getIncomeProgress($documentId, string $goodsId)
    {
        $totalQuantity = 0;

        $leftovers = IncomeDocumentLeftover::query()
            ->with(
                [
                    'package:id,name,main_units_number',
                ])
            ->where('document_id', $documentId)
            ->where('goods_id', $goodsId)
            ->get();

        foreach ($leftovers as $leftover) {
            $totalQuantity += $leftover->quantity * $leftover->package->main_units_number;
        }

        return $totalQuantity;
    }

    public static function getOutcomeProgress($documentId, string $goodsId)
    {
        $totalQuantity = 0;

        $leftovers = OutcomeDocumentLeftover::with(['package', 'leftover', 'leftover.package'])
            ->where('document_id', $documentId)
            ->whereHas('leftover', function ($query) use ($goodsId) {
                $query->where('goods_id', $goodsId);
            })
            ->get();

        foreach ($leftovers as $leftover) {
            $totalQuantity += $leftover->quantity * $leftover->package->main_units_number;
        }

        return $totalQuantity;
    }
}
