<?php

namespace App\Http\Controllers\Web\Document\Income;


use App\Http\Controllers\Controller;
use App\Models\Entities\Document\Document;
use App\Services\Web\Document\Income\IncomeDocumentInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class IncomeDocumentController extends Controller
{
    public function __construct(
        protected IncomeDocumentInterface $service
    ) {}

    public function storeTask(Document $document): JsonResponse
    {
        try {
            $task = $this->service->createTask($document);
            return response()->json($task, Response::HTTP_CREATED);
        } catch (Throwable $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600)
                ? $e->getCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR;

            return response()->json([
                                        'error' => $e->getMessage(),
                                        'exception' => class_basename($e),
                                    ], $statusCode);
        }
    }

    public function process(Document $document): JsonResponse
    {
        try {
            $this->service->process($document);

            return response()->json(['message' => 'Success'], Response::HTTP_CREATED);

        } catch (Throwable $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600)
                ? $e->getCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR;

            return response()->json([
                                        'error' => $e->getMessage(),
                                        'exception' => class_basename($e),
                                    ], $statusCode);
        }
    }

}
