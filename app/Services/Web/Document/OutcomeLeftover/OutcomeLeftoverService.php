<?php

namespace App\Services\Web\Document\OutcomeLeftover;

use App\Http\Requests\Web\Document\OutcomeLeftoverRequest;
use App\Http\Requests\Web\Inventory\LeftoverRequest;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\OutcomeDocumentLeftover;
use App\Models\Entities\Leftover\Leftover;
use App\Services\Web\Leftover\Package\OutcomeUnpackageService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutcomeLeftoverService implements OutcomeLeftoverInterface
{
    public function store(Document $document, Leftover $leftover, float $quantity, string $packageId): array
    {
        return OutcomeDocumentLeftover::create(
            [
                'leftover_id' => $leftover->id,
                'quantity' => $quantity,
                'document_id' => $document->id,
                'processing_at' => now(),
                'processing_type' => 'manual',
                'creator_id' => Auth::id(),
                'package_id' => $packageId
            ])->toArray();
    }

    public function update(OutcomeLeftoverRequest $request, OutcomeDocumentLeftover $outcomeDocumentLeftover): array
    {
        $data = $request->validated();
        $data['processing_at'] = Carbon::now();
        $data['creator_id'] = Auth::id();
        $outcomeDocumentLeftover->update($data);
        return $outcomeDocumentLeftover->toArray();
    }

    public function destroy(OutcomeDocumentLeftover $outcomeDocumentLeftover): bool
    {
        return $outcomeDocumentLeftover->delete() > 0;
    }

    public function manualProcessing(array $data, Document $document): array
    {
        return DB::transaction(function () use ($data, $document) {
            $result = [];

            // Обробка container_registers (без розпакування)
            if (!empty($data['container_registers'])) {
                foreach ($data['container_registers'] as $containerData) {
                    $leftovers = Leftover::where('container_register_id', $containerData['id'])
                        ->lockForUpdate()
                        ->get();

                    foreach ($leftovers as $leftover) {
                        $result[] = $this->store(
                            $document,
                            $leftover,
                            $containerData['quantity'],
                            $leftovers->package_id
                        );
                    }
                }
            }

            // Обробка leftovers (з розпакуванням)
            if (!empty($data['leftovers'])) {
                $unpackageService = new OutcomeUnpackageService();

                foreach ($data['leftovers'] as $leftoverData) {
                    $leftover = Leftover::lockForUpdate()->findOrFail($leftoverData['id']);

                    // Розпаковуємо або повертаємо оригінал
                    $processedLeftover = $unpackageService->reserve(
                        $leftoverData['id'],
                        $document->id,
                        $leftoverData['package_id'],
                        $leftoverData['quantity']
                    );

                    $result[] = $this->store(
                        $document,
                        $processedLeftover,
                        $leftoverData['quantity'],
                        $leftoverData['package_id'],
                    );
                }
            }

            return $result;
        });
    }
}
