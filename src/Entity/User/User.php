<?php

declare(strict_types=1);

namespace App\Entity\User;

class User extends AbstractUser
{
    /** @var string */
    protected $email;

    public function __construct()
    {
        parent::__construct();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
