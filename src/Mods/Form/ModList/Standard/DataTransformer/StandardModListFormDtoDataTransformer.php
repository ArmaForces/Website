<?php

declare(strict_types=1);

namespace App\Mods\Form\ModList\Standard\DataTransformer;

use App\Mods\Entity\ModList\StandardModList;
use App\Mods\Form\ModList\Standard\Dto\StandardModListFormDto;
use App\Shared\Service\IdentifierFactory\IdentifierFactoryInterface;
use App\Users\Entity\Permissions\AbstractPermissions;
use App\Users\Entity\User\User;
use Symfony\Bundle\SecurityBundle\Security;

class StandardModListFormDtoDataTransformer
{
    public function __construct(
        private Security $security,
        private IdentifierFactoryInterface $identifierFactory
    ) {
    }

    public function transformToEntity(
        StandardModListFormDto $standardModListFormDto,
        StandardModList $standardModList = null
    ): StandardModList {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $canUpdate = $currentUser->hasPermissions(
            static fn (AbstractPermissions $permissions) => $permissions->standardModListUpdate
        );

        // If user has permissions set selected user as owner. Otherwise assign current user.
        $owner = $canUpdate ? $standardModListFormDto->getOwner() : $currentUser;

        if (!$standardModList instanceof StandardModList) {
            return new StandardModList(
                $this->identifierFactory->create(),
                $standardModListFormDto->getName(),
                $standardModListFormDto->getDescription(),
                $standardModListFormDto->getMods(),
                $standardModListFormDto->getModGroups(),
                $standardModListFormDto->getDlcs(),
                $owner,
                $standardModListFormDto->isActive(),
                $standardModListFormDto->isApproved(),
            );
        }

        $standardModList->update(
            $standardModListFormDto->getName(),
            $standardModListFormDto->getDescription(),
            $standardModListFormDto->getMods(),
            $standardModListFormDto->getModGroups(),
            $standardModListFormDto->getDlcs(),
            $owner,
            $standardModListFormDto->isActive(),
            $standardModListFormDto->isApproved(),
        );

        return $standardModList;
    }

    public function transformFromEntity(
        StandardModListFormDto $standardModListFormDto,
        StandardModList $standardModList = null,
        bool $copy = false
    ): StandardModListFormDto {
        if (!$standardModList instanceof StandardModList) {
            return $standardModListFormDto;
        }

        $standardModListFormDto->setId($standardModList->getId());
        $standardModListFormDto->setName($standardModList->getName());
        $standardModListFormDto->setDescription($standardModList->getDescription());
        $standardModListFormDto->setMods($standardModList->getMods());
        $standardModListFormDto->setModGroups($standardModList->getModGroups());
        $standardModListFormDto->setDlcs($standardModList->getDlcs());
        $standardModListFormDto->setOwner($standardModList->getOwner());
        $standardModListFormDto->setActive($standardModList->isActive());
        $standardModListFormDto->setApproved($standardModList->isApproved());

        if ($copy) {
            $standardModListFormDto->setId(null); // Entity will be treated as new by the unique name validator
            $standardModListFormDto->setApproved(false); // Reset approval status
        }

        return $standardModListFormDto;
    }
}
