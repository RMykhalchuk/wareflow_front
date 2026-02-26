<?php

namespace App\Services\Web\Leftover\Package;


use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Package;
use Carbon\Carbon;

class UnpackageService extends AbstractUnpackageService
{
    /**
     * Основний метод розпакування
     */
    public static function unpackage(
        string $leftoverId,
        string $packageId,
        int $takeQuantity,
        ?string $cellId = null,
        ?string $containerId = null
    )
    {
        $leftover = Leftover::findOrFail($leftoverId);
        $targetPackage = Package::findOrFail($packageId);

        if ($leftover->package_id === $packageId) {
            // Якщо переносимо всю кількість - просто переміщуємо залишок
            if ($takeQuantity >= $leftover->quantity) {
                if ($cellId) {
                    $leftover->cell_id = $cellId;
                }

                if ($containerId) {
                    $leftover->container_id = $containerId;
                }

                $leftover->container_id = null;

                $leftover->save();
                return $leftover;
            }

            // Якщо переносимо частину - ділимо залишок
            if (!$cellId && !$containerId) {
                throw new \Exception("Cell id or Container id is required");
            }

            if (!$cellId) {
                $container = ContainerRegister::find($containerId);
                $cellId = $container->cell_id;
            }

            // Отримуємо поточний максимальний local_id
            $currentMaxLocalId = Leftover::query()->max('local_id') ?? 0;

            // Створюємо новий залишок з takeQuantity на новій локації
            self::createLeftover($leftover, $packageId, $takeQuantity, $cellId, $containerId, $currentMaxLocalId);

            // Зменшуємо кількість в оригінальному залишку
            $leftover->quantity -= $takeQuantity;

            if ($leftover->quantity == 0) {
                $leftover->deleted_at = Carbon::now();
            }

            $leftover->save();

            return $leftover;
        }


        // Перевіряємо доступну кількість
        $availableInTarget = self::convertToTargetPackage($leftover, $targetPackage);

        if (!$cellId && !$containerId) {
            throw new \Exception(
                "Cell id or Container id is required"
            );
        }

        if (!$cellId) {
            $container = ContainerRegister::find($containerId);
            $cellId = $container->cell_id;
        }

        if ($takeQuantity > $availableInTarget) {
            throw new \Exception(
                "Not enough units. Available: {$availableInTarget} {$targetPackage->name}, requested: {$takeQuantity}"
            );
        }

        // Отримуємо поточний максимальний local_id один раз
        $currentMaxLocalId = Leftover::query()->max('local_id') ?? 0;

        // Створюємо цільовий залишок (переміщений)
        self::createLeftover($leftover, $packageId, $takeQuantity, $cellId, $containerId, $currentMaxLocalId);

        // Оновлюємо батьківські залишки (розпаковуємо ієрархію)
        self::unpackAndUpdate($leftover, $targetPackage, $takeQuantity, $currentMaxLocalId);
    }




}
