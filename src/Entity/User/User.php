<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\ModList\ModListInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User extends AbstractUser
{
    /** @var Collection|ModListInterface[] */
    protected $ownedModLists;

    public function __construct(string $username, string $email, string $externalId)
    {
        parent::__construct($username, $email, $externalId);

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
