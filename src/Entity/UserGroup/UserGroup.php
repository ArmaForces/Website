<?php

declare(strict_types=1);

namespace App\Entity\UserGroup;

use App\Entity\AbstractBlamableEntity;
use App\Entity\Permissions\UserGroupPermissions;
use App\Entity\Traits\DescribedTrait;
use App\Entity\Traits\NamedTrait;
use App\Entity\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class UserGroup extends AbstractBlamableEntity implements UserGroupInterface
{
    use NamedTrait;
    use DescribedTrait;

    protected UserGroupPermissions $permissions;
    protected Collection $users;

    public function __construct(UuidInterface $id, string $name, UserGroupPermissions $permissions)
    {
        parent::__construct($id);

        $this->name = $name;
        $this->permissions = $permissions;

        $this->users = new ArrayCollection();
    }

    public function getPermissions(): UserGroupPermissions
    {
        return $this->permissions;
    }

    public function setPermissions(UserGroupPermissions $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function addUser(UserInterface $user): void
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
    }

    public function removeUser(UserInterface $user): void
    {
        if (!$this->users->contains($user)) {
            return;
        }

        $this->users->removeElement($user);
    }

    public function getUsers(): array
    {
        return $this->users->toArray();
    }

    public function setUsers(array $users): void
    {
        $this->users->clear();
        foreach ($users as $user) {
            $this->addUser($user);
        }
    }
}
