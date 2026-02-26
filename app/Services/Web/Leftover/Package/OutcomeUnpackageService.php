<?php

namespace App\Services\Web\Leftover\Package;


use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Package;
use Carbon\Carbon;



class OutcomeUnpackageService extends AbstractUnpackageService
{
    /**
     * Основний метод розпакування та резервації
     */
    public function reserve(string $leftoverId, string $documentId, string $packageId, int $unpackQuantity)
    {
        $leftover = Leftover::findOrFail($leftoverId);
        $targetPackage = Package::findOrFail($packageId);

        // Якщо пакування однакові, просто повертаємо залишок
        if ($leftover->package_id === $packageId) {
            return $leftover;
        }

        // Перевіряємо доступну кількість
        $availableInTarget = self::convertToTargetPackage($leftover, $targetPackage);

        if ($unpackQuantity > $availableInTarget) {
            throw new \Exception(
                "Not enough units. Available: {$availableInTarget} {$targetPackage->name}, requested: {$unpackQuantity}"
            );
        }

        // Отримуємо поточний максимальний local_id
        $currentMaxLocalId = Leftover::query()->max('local_id') ?? 0;

        // Створюємо цільовий залишок (те що забрали)
        $newLeftover = self::createLeftover(
            $leftover,
            $packageId,
            $unpackQuantity,
            $leftover->cell_id,      // зберігаємо локацію
            $leftover->container_id, // зберігаємо контейнер
            $currentMaxLocalId
        );

        // Оновлюємо батьківські залишки (розпаковуємо ієрархію)
        self::unpackAndUpdate($leftover, $targetPackage, $unpackQuantity, $currentMaxLocalId);

        return $newLeftover;
    }
}
