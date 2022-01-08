<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Security\Voter\Mod;

use App\Entity\Mod\ModInterface;
use App\SharedKernel\Infrastructure\Security\Enum\PermissionsEnum;
use App\UserManagement\Domain\Model\Permissions\PermissionsInterface;
use App\UserManagement\Domain\Model\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UpdateModVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::MOD_UPDATE === $attribute && $subject instanceof ModInterface;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var null|UserInterface $currentUser */
        $currentUser = $token->getUser();
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        return $currentUser->hasPermissions(static fn (PermissionsInterface $permissions) => $permissions->getModManagementPermissions()->canUpdate());
    }
}
