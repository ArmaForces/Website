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

class DeleteModListVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::MOD_LIST_DELETE === $attribute && $subject instanceof ModListInterface;
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
            || $currentUser->hasPermissions(static fn (PermissionsInterface $permissions) => $permissions->getModListManagementPermissions()->canDelete())
        ;
    }
}
