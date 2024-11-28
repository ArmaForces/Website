<?php

declare(strict_types=1);

namespace App\Mods\Security\Voter\Dlc;

use App\Mods\Entity\Dlc\Dlc;
use App\Shared\Security\Enum\PermissionsEnum;
use App\Users\Entity\Permissions\AbstractPermissions;
use App\Users\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeleteDlcVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::DLC_DELETE->value === $attribute && $subject instanceof Dlc;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        if (!$currentUser instanceof User) {
            return false;
        }

        return $currentUser->hasPermissions(static fn (AbstractPermissions $permissions) => $permissions->dlcDelete);
    }
}
