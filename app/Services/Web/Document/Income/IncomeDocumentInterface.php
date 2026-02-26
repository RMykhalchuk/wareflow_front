<?php

namespace App\Services\Web\Document\Income;

use App\Models\Entities\Document\Document;
use App\Models\Entities\Task\Task;

interface IncomeDocumentInterface
{
    public function createTask(Document $document): Task;

    public function process(Document $document): void;
}
