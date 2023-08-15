<?php

declare(strict_types=1);

namespace App\Security\Voter\User;

use App\Entity\Permissions\AbstractPermissions;
use App\Entity\User\User;
use App\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeleteUserVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::USER_DELETE === $attribute && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        if (!$currentUser instanceof User) {
            return false;
        }

        /** @var User $user */
        $user = $subject;

        return $currentUser !== $user
            && $currentUser->hasPermissions(static fn (AbstractPermissions $permissions) => $permissions->userDelete);
    }
}
