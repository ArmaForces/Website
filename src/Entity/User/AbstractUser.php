<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\AbstractEntity;
use App\Entity\Permissions\Permissions;
use App\Entity\User\Traits\UserInterfaceTrait;
use App\Security\Enum\RoleEnum;

class AbstractUser extends AbstractEntity implements UserInterface
{
    use UserInterfaceTrait;

    /** @var string */
    protected $username;

    /** @var string */
    protected $email;

    /** @var string */
    protected $externalId;

    /** @var Permissions */
    protected $permissions;

    /** @var null|string */
    protected $avatarHash;

    public function __construct(string $username, string $email, string $externalId)
    {
        parent::__construct();

        $this->username = $username;
        $this->email = $email;
        $this->externalId = $externalId;
        $this->permissions = new Permissions();
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getPermissions(): Permissions
    {
        return $this->permissions;
    }

    public function setPermissions(Permissions $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function getAvatarHash(): ?string
    {
        return $this->avatarHash;
    }

    public function setAvatarHash(?string $avatarHash): void
    {
        $this->avatarHash = $avatarHash;
    }

    public function getRoles(): array
    {
        return [RoleEnum::ROLE_USER];
    }
}
