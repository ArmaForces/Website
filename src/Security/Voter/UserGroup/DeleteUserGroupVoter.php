<?php

declare(strict_types=1);

namespace App\Security\Voter\UserGroup;

use App\Entity\Permissions\AbstractPermissions;
use App\Entity\User\User;
use App\Entity\UserGroup\UserGroup;
use App\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeleteUserGroupVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::USER_GROUP_DELETE === $attribute && $subject instanceof UserGroup;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        if (!$currentUser instanceof User) {
            return false;
        }

        return $currentUser->hasPermissions(static fn (AbstractPermissions $permissions) => $permissions->userGroupDelete);
    }
}
