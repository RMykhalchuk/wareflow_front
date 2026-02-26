<?php

namespace App\Services\Web\Document\ReserveLeftover;

use App\Http\Requests\Web\Document\OutcomeLeftoverRequest;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\OutcomeDocumentLeftover;

interface ReserveLeftoverInterface
{
    public function reserve(Document $document): void;

    public function removeReservation(Document $document): void;

    public function checkAvailableLeftovers($goods_id) : array;
}
