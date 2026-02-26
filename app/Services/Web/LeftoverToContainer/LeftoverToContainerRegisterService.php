<?php

namespace App\Services\Web\LeftoverToContainer;


use App\Http\Requests\Web\ContainerRegister\LeftoverToContainerRegisterRequest;
use App\Models\Entities\Leftover\LeftoverToContainerRegister;


class LeftoverToContainerRegisterService implements LeftoverToContainerRegisterServiceInterface
{
    public function store(LeftoverToContainerRegisterRequest $request) : array
    {
        $data = $request->validated();

        $containerRegisterId = $data['container_register_id'];
        $leftoverIds = $data['leftover_id_array'];

        $created = [];

        foreach ($leftoverIds as $leftoverId) {
            $created[] = LeftoverToContainerRegister::create(
                [
                    'container_register_id' => $containerRegisterId,
                    'leftover_id' => $leftoverId,
                ]
            );
        }

        return $created;
    }
}
