<?php

declare(strict_types=1);

namespace App\Users\Entity\User;

use App\Shared\Entity\Common\AbstractBlamableEntity;
use App\Shared\Security\Traits\UserInterfaceTrait;
use App\Users\Entity\Permissions\UserPermissions;
use App\Users\Entity\UserGroup\UserGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User extends AbstractBlamableEntity implements UserInterface
{
    use UserInterfaceTrait;

    private string $username;
    private string $email;
    private string $externalId;
    private UserPermissions $permissions;
    private Collection $userGroups;
    private ?string $avatarHash;
    private ?int $steamId;

    public function __construct(
        UuidInterface $id,
        string $username,
        string $email,
        string $externalId,
        UserPermissions $permissions,
        array $userGroups,
        ?string $avatarHash,
        ?int $steamId,
    ) {
        parent::__construct($id);
        $this->userGroups = new ArrayCollection();

        $this->username = $username;
        $this->email = $email;
        $this->externalId = $externalId;
        $this->permissions = $permissions;
        $this->setUserGroups($userGroups);
        $this->avatarHash = $avatarHash;
        $this->steamId = $steamId;
    }

    public function update(
        string $username,
        string $email,
        string $externalId,
        UserPermissions $permissions,
        array $userGroups,
        ?string $avatarHash,
        ?int $steamId,
    ): void {
        $this->username = $username;
        $this->email = $email;
        $this->externalId = $externalId;
        $this->permissions = $permissions;
        $this->setUserGroups($userGroups);
        $this->avatarHash = $avatarHash;
        $this->steamId = $steamId;
    }

    public function getUserIdentifier(): string
    {
        return $this->externalId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getPermissions(): UserPermissions
    {
        return $this->permissions;
    }

    /**
     * @return UserGroup[]
     */
    public function getUserGroups(): array
    {
        return $this->userGroups->toArray();
    }

    public function getAvatarHash(): ?string
    {
        return $this->avatarHash;
    }

    public function getSteamId(): ?int
    {
        return $this->steamId;
    }

    public function hasPermissions(callable $permissionsCheck): bool
    {
        return $permissionsCheck($this->getPermissions())
            || $this->userGroups->exists(
                static fn (int $index, UserGroup $userGroup) => $permissionsCheck($userGroup->getPermissions())
            );
    }

    private function addUserGroup(UserGroup $userGroup): void
    {
        if ($this->userGroups->contains($userGroup)) {
            return;
        }

        $this->userGroups->add($userGroup);
    }

    private function setUserGroups(array $userGroups): void
    {
        $this->userGroups->clear();
        foreach ($userGroups as $userGroup) {
            $this->addUserGroup($userGroup);
        }
    }
}
