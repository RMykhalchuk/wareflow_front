<?php

namespace App\Services\Web\Auth;

class AuthContextService
{
    private ?string $companyId = null;

    public function setCompanyId(?string $id) : void
    {
        $this->companyId = $id;
    }

    public function getCompanyId(): ?string
    {
        return $this->companyId;
    }
}
