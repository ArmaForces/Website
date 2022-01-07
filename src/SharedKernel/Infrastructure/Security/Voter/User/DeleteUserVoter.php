<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Security\Voter\User;

use App\Entity\Permissions\PermissionsInterface;
use App\Entity\User\UserInterface;
use App\SharedKernel\Infrastructure\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeleteUserVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::USER_DELETE === $attribute && $subject instanceof UserInterface;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var null|UserInterface $currentUser */
        $currentUser = $token->getUser();
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        /** @var UserInterface $user */
        $user = $subject;

        return $currentUser !== $user
            && $currentUser->hasPermissions(static fn (PermissionsInterface $permissions) => $permissions->getUserManagementPermissions()->canDelete())
        ;
    }
}
