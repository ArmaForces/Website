<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\AbstractBlamableEntity;
use App\Entity\Permissions\UserPermissions;
use App\Entity\UserGroup\UserGroupInterface;
use App\Security\Traits\UserInterfaceTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class User extends AbstractBlamableEntity implements UserInterface
{
    use UserInterfaceTrait;

    protected string $username;
    protected string $email;
    protected string $externalId;
    protected UserPermissions $permissions;
    protected Collection $userGroups;
    protected ?string $avatarHash = null;
    protected ?int $steamId = null;

    public function __construct(
        UuidInterface $id,
        string $username,
        string $email,
        string $externalId,
        UserPermissions $permissions
    ) {
        parent::__construct($id);

        $this->username = $username;
        $this->email = $email;
        $this->externalId = $externalId;
        $this->permissions = $permissions;

        $this->userGroups = new ArrayCollection();
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

    public function getPermissions(): UserPermissions
    {
        return $this->permissions;
    }

    public function setPermissions(UserPermissions $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function addUserGroup(UserGroupInterface $userGroup): void
    {
        if ($this->userGroups->contains($userGroup)) {
            return;
        }

        $this->userGroups->add($userGroup);
    }

    public function removeUserGroup(UserGroupInterface $userGroup): void
    {
        if (!$this->userGroups->contains($userGroup)) {
            return;
        }

        $this->userGroups->removeElement($userGroup);
    }

    public function getUserGroups(): array
    {
        return $this->userGroups->toArray();
    }

    public function setUserGroups(array $userGroups): void
    {
        $this->userGroups->clear();
        foreach ($userGroups as $userGroup) {
            $this->addUserGroup($userGroup);
        }
    }

    public function getAvatarHash(): ?string
    {
        return $this->avatarHash;
    }

    public function setAvatarHash(?string $avatarHash): void
    {
        $this->avatarHash = $avatarHash;
    }

    public function getSteamId(): ?int
    {
        return $this->steamId;
    }

    public function setSteamId(?int $steamId): void
    {
        $this->steamId = $steamId;
    }

    public function hasPermissions(callable $permissionsCheck): bool
    {
        return $permissionsCheck($this->getPermissions())
            || (new ArrayCollection($this->getUserGroups()))->exists(
                static fn (int $index, UserGroupInterface $userGroup) => $permissionsCheck($userGroup->getPermissions())
            );
    }
}
