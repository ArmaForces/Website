<?php

declare(strict_types=1);

namespace App\Security\Traits;

use App\Security\Enum\RoleEnum;

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

    public function getRoles(): array
    {
        return [RoleEnum::ROLE_USER->value];
    }
}
