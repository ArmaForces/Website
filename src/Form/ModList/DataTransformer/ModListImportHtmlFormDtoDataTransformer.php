<?php

declare(strict_types=1);

namespace App\Form\ModList\DataTransformer;

use App\Entity\EntityInterface;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\Entity\Permissions\PermissionsInterface;
use App\Entity\User\UserInterface;
use App\Form\FormDtoInterface;
use App\Form\ModList\Dto\ModListFormDto;
use App\Form\ModList\Dto\ModListFormDtoHtml;
use App\Form\RegisteredDataTransformerInterface;
use App\Repository\Mod\SteamWorkshopModRepository;
use App\Service\LegacyModListImport\ModListImportHtml;
use App\Service\Steam\Helper\SteamHelper;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Security;

class ModListImportHtmlFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    public function __construct(
        private Security $security,
        private ModListImportHtml $modListImportHtml,
        private SteamWorkshopModRepository $steamWorkshopModRepository,
    ) {
    }

    /**
     * @param ModListFormDtoHtml    $formDto
     * @param null|ModListInterface $entity
     *
     * @return ModListInterface
     */
    public function transformToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): EntityInterface
    {
        $modlistName = $this->modListImportHtml->importFromDirectoryHtml($formDto->getAttachment())->getModlistName();
        $modInfos = $this->modListImportHtml->importFromDirectoryHtml($formDto->getAttachment())->getMods();

        if (!$entity instanceof ModListInterface) {
            $entity = new ModList(Uuid::uuid4(), $modlistName);
        }

        /** @var UserInterface $currentUser */
        $currentUser = $this->security->getUser();

        $canUpdate = $currentUser->hasPermissions(
            static fn (PermissionsInterface $permissions) => $permissions->getModListManagementPermissions()->canUpdate()
        );

        // If user has permissions set selected user as owner. Otherwise assign current user.
        $owner = $canUpdate ? $formDto->getOwner() : $currentUser;

        $entity->setName($modlistName);
        $entity->setOwner($owner);
        $entity->setActive($formDto->isActive());
        $entity->setApproved($formDto->isApproved());

        foreach ($modInfos as $modInfo) {
            if ($modInfo->getUrl()) {
                $modItemId = SteamHelper::itemUrlToItemId($modInfo->getUrl());
                $mod = $this->steamWorkshopModRepository->findOneByItemId($modItemId);
                if (!$mod) {
                    $name = $modInfo->getName();
                    $type = ModTypeEnum::get(ModTypeEnum::REQUIRED);
                    $mod = new SteamWorkshopMod(Uuid::uuid4(), $name, $type, $modItemId);
                }
                $entity->addMod($mod);
            }
        }

        return $entity;
    }

    /**
     * @param ModListFormDto        $formDto
     * @param null|ModListInterface $entity
     *
     * @return ModListFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): FormDtoInterface
    {
        throw new \LogicException('This code should not be reach');
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof ModListFormDtoHtml;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return false;
    }
}
