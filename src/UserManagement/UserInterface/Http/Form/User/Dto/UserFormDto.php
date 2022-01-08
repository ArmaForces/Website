<?php

declare(strict_types=1);

namespace App\UserManagement\UserInterface\Http\Form\User\Dto;

use App\SharedKernel\Infrastructure\Validator\SteamProfileId;
use App\SharedKernel\UserInterface\Http\Form\AbstractFormDto;
use App\UserManagement\Domain\Model\Permissions\UserPermissions;
use App\UserManagement\Infrastructure\Validator\User\UniqueUserSteamId;
use Ramsey\Uuid\UuidInterface;

/**
 * @UniqueUserSteamId(errorPath="steamId")
 */
class UserFormDto extends AbstractFormDto
{
    protected ?UuidInterface $id = null;

    /**
     * @SteamProfileId
     */
    protected ?string $steamId = null;

    protected ?UserPermissions $permissions = null;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function setId(?UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getSteamId(): ?string
    {
        return $this->steamId;
    }

    public function setSteamId(?string $steamId): void
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
