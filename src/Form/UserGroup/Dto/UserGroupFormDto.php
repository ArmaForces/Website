<?php

declare(strict_types=1);

namespace App\Form\UserGroup\Dto;

use App\Entity\Permissions\UserGroupPermissions;
use App\Entity\User\User;
use App\Form\AbstractFormDto;
use App\Validator\UserGroup\UniqueUserGroupName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueUserGroupName(errorPath: 'name')]
class UserGroupFormDto extends AbstractFormDto
{
    protected ?UuidInterface $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    protected ?string $name = null;

    #[Assert\Length(min: 1, max: 255)]
    protected ?string $description = null;

    protected ?UserGroupPermissions $permissions = null;

    protected Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permissions = new UserGroupPermissions(Uuid::uuid4());
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function setId(?UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPermissions(): ?UserGroupPermissions
    {
        return $this->permissions;
    }

    public function setPermissions(?UserGroupPermissions $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function addUser(User $user): void
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
    }

    public function removeUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            return;
        }

        $this->users->removeElement($user);
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users->toArray();
    }

    /**
     * @param User[] $users
     */
    public function setUsers(array $users): void
    {
        $this->users->clear();
        foreach ($users as $user) {
            $this->addUser($user);
        }
    }
}
