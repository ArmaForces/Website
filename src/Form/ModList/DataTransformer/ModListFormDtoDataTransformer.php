<?php

declare(strict_types=1);

namespace App\Form\ModList\DataTransformer;

use App\Entity\ModList\ModList;
use App\Entity\Permissions\AbstractPermissions;
use App\Entity\User\User;
use App\Form\ModList\Dto\ModListFormDto;
use App\Service\IdentifierFactory\IdentifierFactoryInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ModListFormDtoDataTransformer
{
    public function __construct(
        private Security $security,
        private IdentifierFactoryInterface $identifierFactory
    ) {
    }

    public function transformToEntity(ModListFormDto $modListFormDto, ModList $modList = null): ModList
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $canUpdate = $currentUser->hasPermissions(
            static fn (AbstractPermissions $permissions) => $permissions->modListUpdate
        );

        // If user has permissions set selected user as owner. Otherwise assign current user.
        $owner = $canUpdate ? $modListFormDto->getOwner() : $currentUser;

        if (!$modList instanceof ModList) {
            return new ModList(
                $this->identifierFactory->create(),
                $modListFormDto->getName(),
                $modListFormDto->getDescription(),
                $modListFormDto->getMods(),
                $modListFormDto->getModGroups(),
                $modListFormDto->getDlcs(),
                $owner,
                $modListFormDto->isActive(),
                $modListFormDto->isApproved(),
            );
        }

        $modList->update(
            $modListFormDto->getName(),
            $modListFormDto->getDescription(),
            $modListFormDto->getMods(),
            $modListFormDto->getModGroups(),
            $modListFormDto->getDlcs(),
            $owner,
            $modListFormDto->isActive(),
            $modListFormDto->isApproved(),
        );

        return $modList;
    }

    public function transformFromEntity(ModListFormDto $modListFormDto, ModList $modList = null, bool $copy = false): ModListFormDto
    {
        if (!$modList instanceof ModList) {
            return $modListFormDto;
        }

        $modListFormDto->setId($modList->getId());
        $modListFormDto->setName($modList->getName());
        $modListFormDto->setDescription($modList->getDescription());
        $modListFormDto->setMods($modList->getMods());
        $modListFormDto->setModGroups($modList->getModGroups());
        $modListFormDto->setDlcs($modList->getDlcs());
        $modListFormDto->setOwner($modList->getOwner());
        $modListFormDto->setActive($modList->isActive());
        $modListFormDto->setApproved($modList->isApproved());

        if ($copy) {
            $modListFormDto->setId(null); // Entity will be treated as new by the unique name validator
            $modListFormDto->setApproved(false); // Reset approval status
        }

        return $modListFormDto;
    }
}
