<?php

namespace App\Jobs;

use App\Services\Web\Auth\AuthContextService;
use App\Services\Web\LabelGenerator\LabelGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class GenerateLabelsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;
    public int $tries   = 1;

    public function __construct(
        private readonly array  $data,
        private readonly string $jobId,
        private readonly string $companyId,
    ) {}

    public function handle(LabelGeneratorService $service): void
    {
        app(AuthContextService::class)->setCompanyId($this->companyId);

        $filePaths = $service->generateCellLabels($this->data);

        $fileUrls = array_map(
            fn(string $path) => asset('storage/' . $path),
            $filePaths
        );

        Cache::put("label_job_{$this->jobId}", [
            'status'    => 'done',
            'file_urls' => $fileUrls,
        ], now()->addHour());
    }

    public function failed(\Throwable $e): void
    {
        Cache::put("label_job_{$this->jobId}", [
            'status' => 'failed',
            'error'  => $e->getMessage(),
        ], now()->addHour());
    }
}