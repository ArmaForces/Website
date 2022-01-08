<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Security\Voter\ModList;

use App\ModManagement\Domain\Model\ModList\ModListInterface;
use App\SharedKernel\Infrastructure\Security\Enum\PermissionsEnum;
use App\UserManagement\Domain\Model\Permissions\PermissionsInterface;
use App\UserManagement\Domain\Model\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CopyModListVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::MOD_LIST_COPY === $attribute && $subject instanceof ModListInterface;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var null|UserInterface $currentUser */
        $currentUser = $token->getUser();
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        return $currentUser->hasPermissions(static fn (PermissionsInterface $permissions) => $permissions->getModListManagementPermissions()->canCopy());
    }
}
