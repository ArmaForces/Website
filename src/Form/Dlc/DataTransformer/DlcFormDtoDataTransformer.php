<?php

declare(strict_types=1);

namespace App\Form\Dlc\DataTransformer;

use App\Entity\Dlc\Dlc;
use App\Entity\Dlc\DlcInterface;
use App\Entity\EntityInterface;
use App\Entity\Mod\ModInterface;
use App\Form\Dlc\Dto\DlcFormDto;
use App\Form\FormDtoInterface;
use App\Form\RegisteredDataTransformerInterface;
use Ramsey\Uuid\Uuid;

class DlcFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    /**
     * @param DlcFormDto        $formDto
     * @param null|DlcInterface $entity
     *
     * @return DlcInterface
     */
    public function transformToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): EntityInterface
    {
        $appId = 1227700; // TODO: Convert url to app id
        if (!$entity instanceof DlcInterface) {
            $entity = new Dlc(Uuid::uuid4(), $formDto->getName(), $appId);
        }

        $entity->setName($formDto->getName());
        $entity->setDescription($formDto->getDescription());
        $entity->setAppId($appId);

        return $entity;
    }

    /**
     * @param DlcFormDto        $formDto
     * @param null|DlcInterface $entity
     *
     * @return DlcFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): FormDtoInterface
    {
        /** @var ModInterface $entity */
        if (!$entity instanceof DlcInterface) {
            return $formDto;
        }

        $formDto->setId($entity->getId());
        $formDto->setName($entity->getName());
        $formDto->setDescription($entity->getDescription());

        // TODO: Convert app id to url
        $formDto->setUrl('https://store.steampowered.com/app/1227700/Arma_3_Creator_DLC_SOG_Prairie_Fire/');

        return $formDto;
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof DlcFormDto;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof DlcFormDto;
    }
}
