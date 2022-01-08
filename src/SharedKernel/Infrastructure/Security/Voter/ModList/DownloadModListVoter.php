<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Security\Voter\ModList;

use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\SharedKernel\Infrastructure\Security\Enum\PermissionsEnum;
use App\UserManagement\Domain\Model\Permissions\PermissionsInterface;
use App\UserManagement\Domain\Model\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DownloadModListVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::MOD_LIST_DOWNLOAD === $attribute && $subject instanceof ModListInterface;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var null|UserInterface $currentUser */
        $currentUser = $token->getUser();

        /** @var ModList $modList */
        $modList = $subject;

        // User always can download active Mod List
        if ($modList->isActive()) {
            return true;
        }

        // Otherwise user needs to be logged-in and have "List" permission granted
        return $currentUser instanceof UserInterface
            && $currentUser->hasPermissions(static fn (PermissionsInterface $permissions) => $permissions->getModListManagementPermissions()->canList())
        ;
    }
}
