<?php

namespace App\Observers;

use App\Enums\Documents\IncomeDocumentFields;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Task\Task;
use Illuminate\Support\Facades\Log;

class DocumentObserver
{
    public function updated(Document $document): void
    {
        try {
            if ($document->documentType->kind == 'arrival') {
                return;
            }

            $documentData = $document->data();

            // Отримуємо cell_id із кастомного поля
            $cellId = $documentData['header'][IncomeDocumentFields::ALLOCATION->value] ?? null;

            if (!$cellId) {
                Log::warning('DocumentObserver: cell_id not found in document data', [
                    'document_id' => $document->id,
                ]);
                return;
            }

            $task = Task::where('document_id', $document->id)->first();

            if (!$task) {
                return;
            }

            // Оновлюємо таск
            $task->update(['cell_id' => $cellId]);

        } catch (\Throwable $e) {
            Log::error('DocumentObserver: error during update', [
                'document_id' => $document->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
