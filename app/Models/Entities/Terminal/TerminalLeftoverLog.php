<?php

namespace App\Models\Entities\Terminal;

use App\Enums\Task\TaskProcessingType;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\OutcomeDocumentLeftover;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class TerminalLeftoverLog extends Model
{
    protected $guarded = [];

    public static function apply(string $documentId, string $goodsId, TaskProcessingType $processingType, $creatorId): void
    {
        DB::transaction(function () use ($documentId, $goodsId, $processingType, $creatorId) {
            $now = now();

            $logsBuilder = TerminalLeftoverLog::where('document_id', $documentId)
                ->whereHas('leftover', function ($query) use ($goodsId) {
                    $query->where('goods_id', $goodsId);
                });

            $data = $logsBuilder->get()
                ->map(fn ($log) => [
                    'document_id'     => $documentId,
                    'leftover_id'     => $log->leftover_id,
                    'quantity'        => $log->quantity,
                    'creator_id'      => $creatorId,
                    'processing_type' => $processingType->value,
                    'processing_at'   => $now,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ])
                ->toArray();

            $logsBuilder->delete();

            OutcomeDocumentLeftover::insert($data);
        });
    }

    public function revert(): void
    {
        $leftover = Leftover::find($this->leftover_id);

        if ($leftover !== null) {
            $leftover->update(['quantity' => $leftover->quantity + $this->quantity]);
        }

        $this->delete();
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function leftover(): BelongsTo
    {
        return $this->belongsTo(Leftover::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(ContainerRegister::class);
    }
}
