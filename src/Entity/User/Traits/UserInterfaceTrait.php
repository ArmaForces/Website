<?php

declare(strict_types=1);

namespace App\Entity\User\Traits;

trait UserInterfaceTrait
{
    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
