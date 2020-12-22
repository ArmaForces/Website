<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\ModList\ModListInterface;
use App\Entity\Permissions\Permissions;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class User extends AbstractUser
{
    /** @var Collection|ModListInterface[] */
    protected $ownedModLists;

    public function __construct(
        UuidInterface $id,
        string $username,
        string $email,
        string $externalId,
        Permissions $permissions
    ) {
        parent::__construct($id, $username, $email, $externalId, $permissions);

        $this->username = $username;
        $this->email = $email;
        $this->externalId = $externalId;
        $this->permissions = $permissions;

        $this->ownedModLists = new ArrayCollection();
    }

    /**
     * @return Collection|ModListInterface[]
     */
    public function getOwnedModLists()
    {
        return $this->ownedModLists;
    }
}
