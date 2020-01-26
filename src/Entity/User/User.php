<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Permissions\Permissions;

class User extends AbstractUser
{
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
        parent::__construct($username);

        $this->email = $email;
        $this->externalId = $externalId;
        $this->permissions = new Permissions();
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
}
