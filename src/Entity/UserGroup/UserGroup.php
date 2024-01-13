<?php

declare(strict_types=1);

namespace App\Entity\UserGroup;

use App\Entity\AbstractBlamableEntity;
use App\Entity\Permissions\UserGroupPermissions;
use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class UserGroup extends AbstractBlamableEntity
{
    private Collection $users;
    private string $name;
    private ?string $description;
    private UserGroupPermissions $permissions;

    public function __construct(
        UuidInterface $id,
        string $name,
        ?string $description,
        UserGroupPermissions $permissions,
        array $users
    ) {
        parent::__construct($id);
        $this->users = new ArrayCollection();

        $this->name = $name;
        $this->description = $description;
        $this->permissions = $permissions;
        $this->setUsers($users);
    }

    public function update(
        string $name,
        ?string $description,
        UserGroupPermissions $permissions,
        array $users
    ): void {
        $this->name = $name;
        $this->description = $description;
        $this->permissions = $permissions;
        $this->setUsers($users);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPermissions(): UserGroupPermissions
    {
        return $this->permissions;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users->toArray();
    }

    private function setUsers(array $users): void
    {
        $this->users->clear();
        foreach ($users as $user) {
            $this->addUser($user);
        }
    }

    private function addUser(User $user): void
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
    }
}
