<?php

declare(strict_types=1);

namespace App\Security\Voter\ModTag;

use App\Entity\ModTag\ModTagInterface;
use App\Entity\Permissions\PermissionsInterface;
use App\Entity\User\UserInterface;
use App\Security\Enum\PermissionsEnum;
use App\Security\Voter\AbstractVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DeleteModTagVoter extends AbstractVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        return PermissionsEnum::MOD_TAG_DELETE === $attribute && $subject instanceof ModTagInterface;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var null|UserInterface $currentUser */
        $currentUser = $token->getUser();
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        return $this->userHasPermissions($currentUser, static function (PermissionsInterface $permissions) {
            return $permissions->getModTagManagementPermissions()->canDelete();
        });
    }
}
