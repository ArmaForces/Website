<?php

declare(strict_types=1);

namespace App\Security\Voter\ModList;

use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\Entity\User\UserInterface;
use App\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeleteModListVoter extends Voter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        return PermissionsEnum::MOD_LIST_DELETE === $attribute && $subject instanceof ModListInterface;
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

        /** @var ModList $modList */
        $modList = $subject;

        return $modList->getOwner() === $currentUser || $currentUser->getPermissions()->getModListPermissions()->canDelete();
    }
}
