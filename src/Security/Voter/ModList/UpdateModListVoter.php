<?php

declare(strict_types=1);

namespace App\Security\Voter\ModList;

use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\Entity\Permissions\AbstractPermissions;
use App\Entity\User\UserInterface;
use App\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UpdateModListVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::MOD_LIST_UPDATE === $attribute && $subject instanceof ModListInterface;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var null|UserInterface $currentUser */
        $currentUser = $token->getUser();
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        /** @var ModList $modList */
        $modList = $subject;

        return $modList->getOwner() === $currentUser
            || $currentUser->hasPermissions(static fn (AbstractPermissions $permissions) => $permissions->modListUpdate)
        ;
    }
}
