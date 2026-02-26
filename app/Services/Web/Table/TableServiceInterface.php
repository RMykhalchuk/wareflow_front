<?php

namespace App\Services\Web\Table;

use Illuminate\Http\Request;

interface TableServiceInterface
{
    public function createTable(Request $request, string $model): array;

    public function updateTable(Request $request, string $model, int $id): array;
}
