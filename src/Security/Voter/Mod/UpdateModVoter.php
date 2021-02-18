<?php

declare(strict_types=1);

namespace App\Security\Voter\Mod;

use App\Entity\Mod\ModInterface;
use App\Entity\Permissions\PermissionsInterface;
use App\Entity\User\UserInterface;
use App\Security\Enum\PermissionsEnum;
use App\Security\Voter\AbstractVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UpdateModVoter extends AbstractVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::MOD_UPDATE === $attribute && $subject instanceof ModInterface;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var null|UserInterface $currentUser */
        $currentUser = $token->getUser();
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        return $this->userHasPermissions($currentUser, static fn (PermissionsInterface $permissions) => $permissions->getModManagementPermissions()->canUpdate());
    }
}
