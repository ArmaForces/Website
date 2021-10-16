<?php

declare(strict_types=1);

namespace App\Security\Voter\UserGroup;

use App\Entity\Permissions\PermissionsInterface;
use App\Entity\User\UserInterface;
use App\Entity\UserGroup\UserGroupInterface;
use App\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UpdateUserGroupVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::USER_GROUP_UPDATE === $attribute && $subject instanceof UserGroupInterface;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var null|UserInterface $currentUser */
        $currentUser = $token->getUser();
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        return $currentUser->hasPermissions(static fn (PermissionsInterface $permissions) => $permissions->getUserGroupManagementPermissions()->canUpdate());
    }
}
