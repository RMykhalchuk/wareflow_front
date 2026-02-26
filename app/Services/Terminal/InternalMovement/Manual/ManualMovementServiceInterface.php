<?php

namespace App\Services\Terminal\InternalMovement\Manual;

use App\Http\Requests\Terminal\MoveLeftoversOrContainerRequest;
use App\Models\Entities\Container\ContainerRegister;


interface ManualMovementServiceInterface
{
    public function scanContainer(ContainerRegister $container);

    public function scanCell(string $cellId);

    public function move(MoveLeftoversOrContainerRequest $request);

    public function getContainerAndCell(string $query);
}
