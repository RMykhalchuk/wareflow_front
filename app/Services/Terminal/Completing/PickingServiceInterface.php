<?php

namespace App\Services\Terminal\Completing;

use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Terminal\TerminalLeftoverLog;
use App\Models\Entities\WarehouseComponents\Cell;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * CompletingServiceInterface.
 */
interface PickingServiceInterface
{
    public function getLatest(Request $request);

    public function matchProductsByDocumentInCell(Document $document, Cell $cell): array;

    public function moveToControl(Document $document,Goods $goods): void;

    public function pickUpContainer(Document $document, ContainerRegister $containerRegister): void;

    public function takeLeftover(Document $document, $data, $leftoverType): TerminalLeftoverLog;
}
