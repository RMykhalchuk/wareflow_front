<?php

namespace App\Services\Web\Auth;

class GuardContext
{
    private ?string $guard = null;

    public function setGuard(string $guard): void
    {
        $this->guard = $guard;
    }

    public function getGuard(): ?string
    {
        return $this->guard;
    }
}
