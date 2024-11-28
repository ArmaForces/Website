<?php

declare(strict_types=1);

namespace App\Mods\Security\Voter\ModList\Standard;

use App\Mods\Entity\ModList\StandardModList;
use App\Shared\Security\Enum\PermissionsEnum;
use App\Users\Entity\Permissions\AbstractPermissions;
use App\Users\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UpdateStandardModListVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::STANDARD_MOD_LIST_UPDATE->value === $attribute && $subject instanceof StandardModList;
    }

    /**
     * @param StandardModList $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        if (!$currentUser instanceof User) {
            return false;
        }

        return $subject->getOwner() === $currentUser
            || $currentUser->hasPermissions(static fn (AbstractPermissions $permissions) => $permissions->standardModListUpdate);
    }
}
