<?php

declare(strict_types=1);

namespace App\Security\Model;

use App\Security\Traits\UserInterfaceTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiTokenUser implements UserInterface
{
    use UserInterfaceTrait;

    protected string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
