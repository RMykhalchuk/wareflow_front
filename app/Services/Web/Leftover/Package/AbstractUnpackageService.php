<?php

namespace App\Services\Web\Leftover\Package;

use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Package;
use Carbon\Carbon;

abstract class AbstractUnpackageService
{
    /**
     * Конвертує кількість залишку в одиниці цільового пакування
     */
    protected static function convertToTargetPackage(Leftover $leftover, Package $targetPackage): int
    {
        $multiplier = self::getMultiplierBetweenPackages($leftover->package, $targetPackage);

        if ($multiplier === null) {
            throw new \Exception("Target package is not a child of leftover's package");
        }

        return $leftover->quantity * $multiplier;
    }

    /**
     * Обчислює скільки targetPackage в одному fromPackage
     */
    protected static function getMultiplierBetweenPackages(?Package $fromPackage, Package $targetPackage): ?int
    {
        if (!$fromPackage) {
            return null;
        }

        if ($fromPackage->id === $targetPackage->id) {
            return 1;
        }

        if (!$fromPackage->child) {
            return null;
        }

        $child = $fromPackage->child;
        $childCount = $child->package_count ?? 1;

        if ($child->id === $targetPackage->id) {
            return $childCount;
        }

        $deeper = self::getMultiplierBetweenPackages($child, $targetPackage);
        return $deeper !== null ? $childCount * $deeper : null;
    }

    /**
     * Створює новий залишок (копію з іншими параметрами)
     */
    protected static function createLeftover(
        Leftover $leftover,
        string $packageId,
        int $quantity,
        ?string $cellId = null,
        ?string $containerId = null,
        int &$currentMaxLocalId = null
    ): Leftover
    {
        $package = Package::findOrFail($packageId);

        // Створюємо новий залишок
        $newLeftover = new Leftover();

        // Копіюємо всі атрибути крім id та deleted_at
        $attributes = $leftover->getAttributes();
        unset($attributes['id']);         // видаляємо id щоб Laravel створив новий
        unset($attributes['deleted_at']); // видаляємо deleted_at щоб новий залишок не був "видаленим"

        // Використовуємо forceFill щоб обійти $fillable/$guarded
        $newLeftover->forceFill($attributes);

        // Інкрементуємо local_id
        if ($currentMaxLocalId !== null) {
            $currentMaxLocalId++;
            $newLeftover->local_id = $currentMaxLocalId;
        } else {
            // Fallback якщо не передано (для зворотної сумісності)
            $newLocalId = Leftover::query()->max('local_id') ?? 0;
            $newLeftover->local_id = $newLocalId + 1;
        }

        // Змінюємо тільки потрібні поля
        $newLeftover->quantity = $quantity;
        $newLeftover->package_id = $package->id;
        $newLeftover->parent_id = $leftover->id;
        $newLeftover->created_at = Carbon::now();
        $newLeftover->updated_at = Carbon::now();


        // Встановлюємо нову локацію тільки якщо вона передана
        if ($cellId !== null) {
            $newLeftover->cell_id = $cellId;
        }
        if ($containerId !== null) {
            $newLeftover->container_id = $containerId;
        }

        $newLeftover->save();

        return $newLeftover;
    }

    /**
     * Розпаковує та оновлює ієрархію залишків
     */
    protected static function unpackAndUpdate(
        Leftover $leftover,
        Package $targetPackage,
        int $takeQuantity,
        int &$currentMaxLocalId
    ): void
    {
        // Отримуємо поточне пакування
        $currentPackageId = $leftover->package_id;

        if (!$currentPackageId) {
            throw new \Exception("Leftover has no package");
        }

        // Якщо поточне пакування == цільове, просто віднімаємо
        if ($currentPackageId === $targetPackage->id) {
            $leftover->quantity -= $takeQuantity;

            if ($leftover->exists) {
                $leftover->save();
            }
            return;
        }

        // Завантажуємо повний об'єкт пакування з child
        $currentPackage = Package::with('child')->findOrFail($currentPackageId);

        // Йдемо на рівень нижче
        $childPackage = $currentPackage->child;

        if (!$childPackage) {
            throw new \Exception("Cannot unpack further - no child package");
        }

        // Скільки child в одному parent
        $childPerParent = $childPackage->package_count ?? 1;

        // Скільки child одиниць нам потрібно для takeQuantity цільових одиниць
        $childMultiplier = self::getMultiplierBetweenPackages($childPackage, $targetPackage);

        if ($childMultiplier === null) {
            throw new \Exception("Target is not reachable from child package");
        }

        $childUnitsNeeded = (int)ceil($takeQuantity / $childMultiplier);

        // Скільки батьківських пакувань треба розпакувати
        $parentUnitsToUnpack = (int)ceil($childUnitsNeeded / $childPerParent);

        // Перевіряємо чи достатньо
        if ($parentUnitsToUnpack > $leftover->quantity) {
            throw new \Exception("Not enough parent units");
        }

        $newQuantity = $leftover->quantity - $parentUnitsToUnpack;

        $leftover->quantity = $newQuantity;

        if ($newQuantity == 0) {
            $leftover->deleted_at = Carbon::now();
        }

        // Перевіряємо чи це реальний запис з БД
        if ($leftover->exists) {
            $leftover->save();
        }

        // Загальна кількість child після розпакування
        $totalChildUnits = $parentUnitsToUnpack * $childPerParent;

        // Створюємо залишок з child одиницями (ті що залишились після взяття)
        $remainingChildUnits = $totalChildUnits - $childUnitsNeeded;

        if ($remainingChildUnits > 0) {
            self::createLeftover(
                $leftover,
                $childPackage->id,
                $remainingChildUnits,
                $leftover->cell_id,
                $leftover->container_id,
                $currentMaxLocalId
            );
        }

        // Створюємо тимчасовий залишок для подальшого розпакування
        $tempLeftover = new Leftover();

        // Копіюємо всі атрибути з оригіналу (включно з batch)
        $tempAttributes = $leftover->getAttributes();
        unset($tempAttributes['id']); // видаляємо id
        $tempLeftover->forceFill($tempAttributes);

        // Перезаписуємо тільки потрібні поля
        $tempLeftover->quantity = $childUnitsNeeded;
        $tempLeftover->package_id = $childPackage->id;
        $tempLeftover->parent_id = $leftover->id;
        // НЕ викликаємо save() - це тимчасовий об'єкт

        // Рекурсивно продовжуємо розпакування
        self::unpackAndUpdate($tempLeftover, $targetPackage, $takeQuantity, $currentMaxLocalId);
    }

    /**
     * Обчислює множник пакування для залишку
     */
    protected static function getUnitMultiplier(Leftover $leftover): int
    {
        $package = $leftover->package;

        if (!$package) {
            return 1;
        }

        return self::calculatePackageMultiplier($package);
    }

    /**
     * Рекурсивно обчислює множник пакування
     */
    protected static function calculatePackageMultiplier(Package $package, array $visited = []): int
    {
        if (in_array($package->id, $visited, true)) {
            return 1;
        }

        $visited[] = $package->id;

        if (!$package->child) {
            return $package->package_count ?? 1;
        }

        $childCount = $package->child->package_count ?? 1;
        return $childCount * self::calculatePackageMultiplier($package->child, $visited);
    }
}
