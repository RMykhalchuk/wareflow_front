<?php

namespace App\Observers;

use App\Enums\ContainerRegister\ContainerRegisterStatus;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Leftover\Leftover;

class LeftoverObserver
{
    public function created(Leftover $leftover): void
    {
        $this->setContainerWithProduct($leftover->container_id);
    }

    public function updated(Leftover $leftover): void
    {
        if ($leftover->isDirty('container_id')) {
            $oldContainerId = $leftover->getOriginal('container_id');

            $this->setContainerWithProduct($leftover->container_id);
            $this->recalculateContainerStatus($oldContainerId);
        }
    }

    public function deleted(Leftover $leftover): void
    {
        $this->recalculateContainerStatus($leftover->container_id);
    }

    public function restored(Leftover $leftover): void
    {
        $this->setContainerWithProduct($leftover->container_id);
    }

    private function setContainerWithProduct(?string $containerId): void
    {
        if (!$containerId) {
            return;
        }

        ContainerRegister::where('id', $containerId)
            ->where('status_id', '!=', ContainerRegisterStatus::WITH_PRODUCT)
            ->update(['status_id' => ContainerRegisterStatus::WITH_PRODUCT]);
    }

    private function recalculateContainerStatus(?string $containerId): void
    {
        if (!$containerId) {
            return;
        }

        $container = ContainerRegister::find($containerId);

        if (!$container || $container->status_id === ContainerRegisterStatus::DEACTIVATED) {
            return;
        }

        $hasLeftovers = $container->leftovers()->exists();

        $container->update([
            'status_id' => $hasLeftovers
                ? ContainerRegisterStatus::WITH_PRODUCT
                : ContainerRegisterStatus::EMPTY,
        ]);
    }
}
