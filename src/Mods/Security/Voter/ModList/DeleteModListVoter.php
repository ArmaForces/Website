<?php

declare(strict_types=1);

namespace App\Mods\Security\Voter\ModList;

use App\Mods\Entity\ModList\ModList;
use App\Shared\Security\Enum\PermissionsEnum;
use App\Users\Entity\Permissions\AbstractPermissions;
use App\Users\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeleteModListVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::MOD_LIST_DELETE->value === $attribute && $subject instanceof ModList;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        if (!$currentUser instanceof User) {
            return false;
        }

        /** @var ModList $modList */
        $modList = $subject;

        return $modList->getOwner() === $currentUser
            || $currentUser->hasPermissions(static fn (AbstractPermissions $permissions) => $permissions->modListDelete);
    }
}
