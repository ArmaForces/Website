<?php

declare(strict_types=1);

namespace App\Security\Voter\User;

use App\Entity\User\User;
use App\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class DeleteUserVoter extends Voter
{
    /**
     * @param string $attribute
     * @param User   $subject
     */
    protected function supports($attribute, $subject): bool
    {
        return PermissionsEnum::USER_DELETE === $attribute && $subject instanceof UserInterface;
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

        if ($user->getId() !== $subject->getId() && $user->getPermissions()->getUserPermissions()->canDelete()) {
            return true;
        }

        return false;
    }
}
