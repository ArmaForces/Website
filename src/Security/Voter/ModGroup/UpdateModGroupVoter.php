<?php

declare(strict_types=1);

namespace App\Security\Voter\ModGroup;

use App\Entity\ModGroup\ModGroupInterface;
use App\Entity\User\UserInterface;
use App\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UpdateModGroupVoter extends Voter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        return PermissionsEnum::MOD_GROUP_UPDATE === $attribute && $subject instanceof ModGroupInterface;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var null|UserInterface $currentUser */
        $currentUser = $token->getUser();
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        return $currentUser->getPermissions()->getModGroupManagementPermissions()->canUpdate();
    }
}
