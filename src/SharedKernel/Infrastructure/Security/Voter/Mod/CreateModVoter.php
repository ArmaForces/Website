<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Security\Voter\Mod;

use App\Entity\Permissions\PermissionsInterface;
use App\Entity\User\UserInterface;
use App\SharedKernel\Infrastructure\Security\Enum\PermissionsEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CreateModVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::MOD_CREATE === $attribute;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var null|UserInterface $currentUser */
        $currentUser = $token->getUser();
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        return $currentUser->hasPermissions(static fn (PermissionsInterface $permissions) => $permissions->getModManagementPermissions()->canCreate());
    }
}
