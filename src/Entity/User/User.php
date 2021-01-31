<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\AbstractEntity;
use App\Entity\Permissions\Permissions;
use App\Entity\User\Traits\UserInterfaceTrait;
use App\Entity\UserGroup\UserGroupInterface;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class User extends AbstractEntity implements UserInterface
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

    /** @var Collection|UserGroupInterface[] */
    protected $userGroups;

    /** @var null|string */
    protected $avatarHash;

    public function __construct(
        UuidInterface $id,
        string $username,
        string $email,
        string $externalId,
        Permissions $permissions
    ) {
        parent::__construct($id);

        $this->username = $username;
        $this->email = $email;
        $this->externalId = $externalId;
        $this->permissions = $permissions;
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
}
