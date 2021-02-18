<?php

declare(strict_types=1);

namespace App\Security\Voter\User;

use App\Entity\Permissions\PermissionsInterface;
use App\Entity\User\UserInterface;
use App\Security\Enum\PermissionsEnum;
use App\Security\Voter\AbstractVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ManageUserPermissionsVoter extends AbstractVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports(string $attribute, $subject): bool
    {
        return PermissionsEnum::USER_PERMISSIONS_MANAGE === $attribute && $subject instanceof UserInterface;
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

        return $this->userHasPermissions($currentUser, static function (PermissionsInterface $permissions) {
            return $permissions->getUserManagementPermissions()->canManagePermissions();
        });
    }
}
