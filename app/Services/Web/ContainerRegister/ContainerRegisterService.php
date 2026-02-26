<?php

namespace App\Services\Web\ContainerRegister;

use App\Models\Entities\Container\Container;
use App\Models\Entities\Container\ContainerRegister;

class ContainerRegisterService implements ContainerRegisterServiceInterface
{

    public function store(array $data)
    {
        $count = $data['count'] ?? 1;
        unset($data['count']);

        $codeFormat = Container::findOrFail($data['container_id'])->code_format;

        $containerRegister = ContainerRegister::where('container_id', $data['container_id'])
            ->latest('local_id')
            ->first();

        // Визначаємо останнє число
        if ($containerRegister) {
            preg_match('/^(.{5})(\d+)$/', $containerRegister->code, $matches);
            $prefix = $matches[1] ?? $codeFormat;
            $lastNumber = (int)($matches[2] ?? 0);
            $numberLength = strlen($matches[2]);
        } else {
            $prefix = $codeFormat;
            $lastNumber = 0;
            $numberLength = 10;
        }

        $createdRegisters = [];

        for ($i = 1; $i <= $count; $i++) {
            $nextNumber = str_pad($lastNumber + $i, $numberLength, '0', STR_PAD_LEFT);
            $data['code'] = $prefix . $nextNumber;
            $createdRegisters[] = ContainerRegister::create($data);
        }

        return $createdRegisters;
    }


}
