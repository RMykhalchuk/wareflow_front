<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateLabelsJob;
use App\Services\Web\Auth\AuthContextService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PrintLabelController extends Controller
{
    public function generateCellLabels(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type'      => 'required|string|in:cell,zone,sector,container,row',
            'items'     => 'nullable|array',
            'items.*'   => 'string',
            'item_from' => 'nullable|string',
            'item_to'   => 'nullable|string',
            'repeat'    => 'nullable|integer|min:1|max:10',
        ]);

        $jobId = Str::uuid()->toString();

        Cache::put("label_job_{$jobId}", ['status' => 'pending'], now()->addHour());

        $companyId = app(AuthContextService::class)->getCompanyId();

        GenerateLabelsJob::dispatch($validated, $jobId, $companyId);

        return response()->json(['job_id' => $jobId]);
    }

    public function checkStatus(string $jobId): JsonResponse
    {
        $data = Cache::get("label_job_{$jobId}");

        if ($data === null) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json($data);
    }
}