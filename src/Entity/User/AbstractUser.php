<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\AbstractEntity;
use Symfony\Component\Security\Core\User\UserInterface;

class AbstractUser extends AbstractEntity implements UserInterface
{
    /** @var string */
    protected $username;

    /** @var null|string */
    protected $password;

    /** @var null|string */
    protected $salt;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(?string $salt): void
    {
        $this->salt = $salt;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
    }
}
