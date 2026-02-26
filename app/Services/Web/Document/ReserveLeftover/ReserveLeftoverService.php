<?php

namespace App\Services\Web\Document\ReserveLeftover;

use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\DocumentLeftoverReservation;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Package;

class ReserveLeftoverService implements ReserveLeftoverInterface
{
    public function reserve(Document $document): void
    {
        $documentProducts = $document->data()['sku_table'];

        $reservationInsertData = [];

        foreach ($documentProducts as $documentProduct) {
            $reservationInsertData[] = [
                'document_id' => $document->id,
                'goods_id' => $documentProduct['id'],
                'quantity' => $documentProduct['quantity'],
            ];
        }

        DocumentLeftoverReservation::insert($reservationInsertData);
    }

    public function removeReservation(Document $document): void
    {
        DocumentLeftoverReservation::where('document_id', $document->id)->delete();
    }

    public function checkAvailableLeftovers($goods_id): array
    {
        $leftovers = Leftover::with(['package'])->where('goods_id', $goods_id)
            ->where('is_reserved', false)->get();

        $goodsPackage = Package::where('goods_id', $goods_id)->whereNull('parent_id')->first();

        if (!$goodsPackage) {
            return [0, 1];
        }

        $availableMainUnitsCount = 0;
        foreach ($leftovers as $leftover) {
            if (!$leftover->package) {
                continue;
            }
            $availableMainUnitsCount += $leftover->quantity * $leftover->package->main_units_number;
        }

        $reservations = DocumentLeftoverReservation::where('goods_id', $goods_id)->get();

        $reservedUnitsCount = 0;

        foreach ($reservations as $reservation) {
            $reservedUnitsCount += $reservation->quantity;
        }

        $availableQuantity = $availableMainUnitsCount - $reservedUnitsCount;

        $unitsInPackageQuantity = $goodsPackage->main_units_number;

        if (!$unitsInPackageQuantity) {
            return [0, 1];
        }

        // округлити вниз до найближчого кратного
        $maxAvailableQuantity = floor($availableQuantity / $unitsInPackageQuantity) * $unitsInPackageQuantity;

        return [$maxAvailableQuantity, $unitsInPackageQuantity];
    }
}
