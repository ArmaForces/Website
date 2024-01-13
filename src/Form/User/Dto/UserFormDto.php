<?php

declare(strict_types=1);

namespace App\Form\User\Dto;

use App\Entity\Permissions\UserPermissions;
use App\Validator\SteamProfileId;
use App\Validator\User\UniqueUserSteamId;
use Ramsey\Uuid\UuidInterface;

#[UniqueUserSteamId(errorPath: 'steamId')]
class UserFormDto
{
    private ?UuidInterface $id = null;

    #[SteamProfileId]
    private ?int $steamId = null;

    private ?UserPermissions $permissions = null;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function setId(?UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getSteamId(): ?int
    {
        return $this->steamId;
    }

    public function setSteamId(?int $steamId): void
    {
        $this->steamId = $steamId;
    }

    public function getPermissions(): ?UserPermissions
    {
        return $this->permissions;
    }

    public function setPermissions(?UserPermissions $permissions): void
    {
        $this->permissions = $permissions;
    }
}
