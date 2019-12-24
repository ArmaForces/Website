<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\AbstractEntity;
use App\Security\Enum\RoleEnum;
use Symfony\Component\Security\Core\User\UserInterface;

class AbstractUser extends AbstractEntity implements UserInterface
{
    /** @var string */
    protected $username;

    /** @var null|string */
    protected $password;

    /** @var null|string */
    protected $salt;

    /** @var string */
    protected $role = RoleEnum::ROLE_USER;

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

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getRoles(): array
    {
        return [
            $this->getRole(),
        ];
    }

    public function eraseCredentials(): void
    {
    }
}
