<?php

declare(strict_types=1);

namespace App\Security\Voter\ModList;

use App\Entity\ModList\ModList;
use App\Entity\Permissions\AbstractPermissions;
use App\Entity\User\User;
use App\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DownloadModListVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::MOD_LIST_DOWNLOAD === $attribute && $subject instanceof ModList;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();

        /** @var ModList $modList */
        $modList = $subject;

        // User always can download active Mod List
        if ($modList->isActive()) {
            return true;
        }

        // Otherwise user needs to be logged-in and have "List" permission granted
        return $currentUser instanceof User
            && $currentUser->hasPermissions(static fn (AbstractPermissions $permissions) => $permissions->modListList);
    }
}
