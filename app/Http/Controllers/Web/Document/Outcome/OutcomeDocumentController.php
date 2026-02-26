<?php

namespace App\Http\Controllers\Web\Document\Outcome;

use App\Http\Controllers\Controller;
use App\Models\Entities\Document\Document;
use App\Services\Web\Document\Outcome\OutcomeDocumentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class OutcomeDocumentController extends Controller
{
    public function __construct(private OutcomeDocumentServiceInterface $outcomeDocumentService) {}

    public function storeTask(Document $document): JsonResponse
    {
        try {
            $task = $this->outcomeDocumentService->createPickingTask($document);
            return response()->json($task, Response::HTTP_CREATED);
        } catch (Throwable $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600)
                ? $e->getCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR;

            return response()->json(
                [
                    'error' => $e->getMessage(),
                    'exception' => class_basename($e),
                ], $statusCode);
        }
    }

    public function process(Document $document): JsonResponse
    {
        try {
            $this->outcomeDocumentService->process($document);
            return response()->json(['message' => 'Success'], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600)
                ? $e->getCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR;

            return response()->json(
                [
                    'error' => $e->getMessage(),
                    'exception' => class_basename($e),
                ], $statusCode);
        }
    }
}
