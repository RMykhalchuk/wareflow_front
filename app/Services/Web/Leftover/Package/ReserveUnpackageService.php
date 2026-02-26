<?php

namespace App\Services\Web\Leftover\Package;


use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Package;
use Carbon\Carbon;


// Логіка резервації помінялась в комплектуванні
class ReserveUnpackageService extends AbstractUnpackageService
{
    /**
     * Основний метод розпакування та резервації
     */
    public function reserve(string $leftoverId, string $documentId, string $packageId, int $reserveQuantity)
    {
        $leftover = Leftover::findOrFail($leftoverId);
        $targetPackage = Package::findOrFail($packageId);

        if ($leftover->package_id === $packageId) {
            // Якщо переносимо всю кількість - просто переміщуємо залишок
            if ($reserveQuantity >= $leftover->quantity) {
                $leftover->is_reserved = null;

                $leftover->save();
                return $leftover;
            }

            // Отримуємо поточний максимальний local_id
            $currentMaxLocalId = Leftover::query()->max('local_id') ?? 0;

            // Створюємо новий залишок з takeQuantity на новій локації
            self::createLeftover($leftover, $documentId, $packageId, $reserveQuantity, $currentMaxLocalId);

            // Зменшуємо кількість в оригінальному залишку
            $leftover->quantity -= $reserveQuantity;

            if ($leftover->quantity == 0) {
                $leftover->deleted_at = Carbon::now();
            }

            $leftover->save();

            return $leftover;
        }


        // Перевіряємо доступну кількість
        $availableInTarget = self::convertToTargetPackage($leftover, $targetPackage);

        if ($reserveQuantity > $availableInTarget) {
            throw new \Exception(
                "Not enough units. Available: {$availableInTarget} {$targetPackage->name}, requested: {$reserveQuantity}"
            );
        }

        // Отримуємо поточний максимальний local_id один раз
        $currentMaxLocalId = Leftover::query()->max('local_id') ?? 0;

        // Створюємо цільовий залишок (зарезервовантй)
        self::createLeftover($leftover, $documentId, $packageId, $reserveQuantity, $currentMaxLocalId);

        // Оновлюємо батьківські залишки (розпаковуємо ієрархію)
        self::unpackAndUpdate($leftover, $targetPackage, $reserveQuantity, $currentMaxLocalId);

        return  $leftover;
    }
}
