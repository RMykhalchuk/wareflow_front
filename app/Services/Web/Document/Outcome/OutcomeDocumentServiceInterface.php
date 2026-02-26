<?php

namespace App\Services\Web\Document\Outcome;

use App\Models\Entities\Document\Document;

interface OutcomeDocumentServiceInterface {
    public function createPickingTask(Document $document);
    public function createControlTask(Document $document);
    public function process(Document $document): void;
}
