<?php

namespace App\Services\Web\Table;

use App\Factories\TableModelFactory;
use Exception;
use Illuminate\Http\Request;

class TableService implements TableServiceInterface
{
    private $factory;

    public function __construct(TableModelFactory $factory)
    {
        $this->factory = $factory;
    }

    public function createTable(Request $request, string $model): array
    {
        $data = $request->all();
        unset($data['model']);
        if (!is_callable([$this->factory, $model])) {
            throw new Exception('Model not Exist');
        }

        $this->factory->$model()->create($data);

        return ['message' => 'OK', 'status' => 201];
    }

    public function updateTable(Request $request, string $model, int $id): array
    {
        $data = $request->all();
        if (!is_callable([$this->factory, $model])) {
            throw new Exception('Model not Exist');
        }
        $this->factory->$model()->find($id)->update($data);

        return ['message' => 'OK', 'status' => 204];
    }
}
