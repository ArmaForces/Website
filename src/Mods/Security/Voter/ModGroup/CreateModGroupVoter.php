<?php

declare(strict_types=1);

namespace App\Mods\Security\Voter\ModGroup;

use App\Shared\Security\Enum\PermissionsEnum;
use App\Users\Entity\Permissions\AbstractPermissions;
use App\Users\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CreateModGroupVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::MOD_GROUP_CREATE->value === $attribute;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        if (!$currentUser instanceof User) {
            return false;
        }

        return $currentUser->hasPermissions(static fn (AbstractPermissions $permissions) => $permissions->modGroupCreate);
    }
}
