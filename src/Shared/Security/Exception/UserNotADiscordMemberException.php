<?php

declare(strict_types=1);

namespace App\Shared\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserNotADiscordMemberException extends AuthenticationException
{
}
