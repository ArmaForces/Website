<?php

declare(strict_types=1);

namespace App\Security\Voter\User;

use App\Entity\User\User;
use App\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ManageUsersPermissionsVoter extends Voter
{
    /**
     * @param string $attribute
     * @param User   $subject
     */
    protected function supports($attribute, $subject): bool
    {
        return PermissionsEnum::USERS_MANAGE_PERMISSIONS === $attribute && $subject instanceof UserInterface;
    }

    /**
     * @param string $attribute
     * @param User   $subject
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($user->getPermissions()->getUsersPermissions()->canManagePermissions()) {
            return true;
        }

        return false;
    }
}
