<?php

namespace App\Services\Web\LeftoverToContainer;

use App\Http\Requests\Web\ContainerRegister\LeftoverToContainerRegisterRequest;


interface LeftoverToContainerRegisterServiceInterface
{
    public function store(LeftoverToContainerRegisterRequest $request): array;
}
