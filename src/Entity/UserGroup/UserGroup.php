<?php

declare(strict_types=1);

namespace App\Entity\UserGroup;

use App\Entity\AbstractDescribedEntity;
use App\Entity\Permissions\Permissions;
use App\Entity\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class UserGroup extends AbstractDescribedEntity
{
    /** @var Permissions */
    protected $permissions;

    /** @var Collection|UserInterface[] */
    protected $users;

    public function __construct(UuidInterface $id, string $name, Permissions $permissions)
    {
        parent::__construct($id, $name);

        $this->permissions = $permissions;
    }

    public function getPermissions(): Permissions
    {
        return $this->permissions;
    }

    public function setPermissions(Permissions $permissions): void
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
