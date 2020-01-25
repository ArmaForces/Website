<?php

declare(strict_types=1);

namespace App\Security\Voter\User;

use App\Entity\User\UserEntity;
use App\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ListUsersVoter extends Voter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        return PermissionsEnum::USERS_LIST === $attribute;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var UserEntity $user */
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return $user->getPermissions()->getUsersPermissions()->canList();
    }
}
