<?php

namespace App\Http\Controllers\Web;

use App\Factories\TableModelFactory;
use App\Http\Controllers\Controller;
use App\Http\Resources\Web\TableCollectionResource;
use App\Services\Web\Table\TableServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

final class TableController extends Controller
{
    private $factory;
    private $tableService;

    public function __construct(TableModelFactory $factory, TableServiceInterface $tableService)
    {
        $this->factory = $factory;
        $this->tableService = $tableService;
    }

    public function create(Request $request): Response
    {
        $model = $request->input('model');
        $result = $this->tableService->createTable($request, $model);
        return response($result['message'], $result['status']);
    }

    public function update(Request $request, $model, $id): Response
    {
        $result = $this->tableService->updateTable($request, $model, $id);
        return response($result['message'], $result['status']);
    }

    public function delete($model, $id): Response
    {
        $this->factory->$model()?->find($id)?->delete();
        return response('OK');
    }

    public function index($model): AnonymousResourceCollection
    {
        return TableCollectionResource::collection($this->factory->$model()::paginate(15));
    }
}
