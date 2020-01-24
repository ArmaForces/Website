<?php

declare(strict_types=1);

namespace App\Security\Voter\User;

use App\Entity\User\UserEntity;
use App\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ManageUsersPermissionsVoter extends Voter
{
    /**
     * @param string     $attribute
     * @param UserEntity $subject
     */
    protected function supports($attribute, $subject): bool
    {
        return PermissionsEnum::MANAGE_USERS_PERMISSIONS === $attribute && $subject instanceof UserInterface;
    }

    /**
     * @param string     $attribute
     * @param UserEntity $subject
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var UserEntity $user */
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($user->getPermissions()->canManageUsersPermissions()) {
            return true;
        }

        return false;
    }
}
