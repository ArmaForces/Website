<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Security\Enum\RoleEnum;

trait UserInterfaceTrait
{
    public function getRoles(): array
    {
        return [RoleEnum::ROLE_USER];
    }

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
