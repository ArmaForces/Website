<?php

declare(strict_types=1);

namespace App\Mods\Security\Voter\ModList\Common;

use App\Mods\Entity\ModList\AbstractModList;
use App\Shared\Security\Enum\PermissionsEnum;
use App\Users\Entity\Permissions\AbstractPermissions;
use App\Users\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DownloadModListVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::MOD_LIST_DOWNLOAD->value === $attribute && $subject instanceof AbstractModList;
    }

    /**
     * @param AbstractModList $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();

        // User always can download active Mod List
        if ($subject->isActive()) {
            return true;
        }

        // Otherwise user needs to be logged-in and have "List" permission granted
        return $currentUser instanceof User
            && $currentUser->hasPermissions(static fn (AbstractPermissions $permissions) => $permissions->modListList);
    }
}
