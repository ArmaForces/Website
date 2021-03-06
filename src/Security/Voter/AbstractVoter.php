<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User\UserInterface;
use App\Entity\UserGroup\UserGroupInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractVoter extends Voter
{
    public static function userHasPermissions(UserInterface $user, callable $permissionsCheck): bool
    {
        return $permissionsCheck($user->getPermissions())
            || (new ArrayCollection($user->getUserGroups()))->exists(
                static fn (int $index, UserGroupInterface $userGroup) => $permissionsCheck($userGroup->getPermissions())
            );
    }
}
