<?php

declare(strict_types=1);

namespace App\Form\Dlc\DataTransformer;

use App\Entity\Dlc\Dlc;
use App\Entity\Dlc\DlcInterface;
use App\Entity\EntityInterface;
use App\Form\Dlc\Dto\DlcFormDto;
use App\Form\FormDtoInterface;
use App\Form\RegisteredDataTransformerInterface;
use App\Service\Steam\Helper\SteamHelper;
use App\Service\Steam\SteamApiClient;
use Ramsey\Uuid\Uuid;

class DlcFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    public function __construct(
        private SteamApiClient $steamApiClient
    ) {
    }

    /**
     * @param DlcFormDto        $formDto
     * @param null|DlcInterface $entity
     *
     * @return DlcInterface
     */
    public function transformToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): EntityInterface
    {
        $appId = SteamHelper::appUrlToAppId($formDto->getUrl());
        $directory = $formDto->getDirectory();
        $name = $formDto->getName() ?? substr($this->steamApiClient->getAppInfo($appId)->getName(), 0, 255);

        if (!$entity instanceof DlcInterface) {
            $entity = new Dlc(Uuid::uuid4(), $name, $appId, $directory);
        }

        $entity->setName($name);
        $entity->setDescription($formDto->getDescription());
        $entity->setAppId($appId);
        $entity->setDirectory($directory);

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
        /** @var DlcInterface $entity */
        if (!$entity instanceof DlcInterface) {
            return $formDto;
        }

        $formDto->setId($entity->getId());
        $formDto->setName($entity->getName());
        $formDto->setDescription($entity->getDescription());
        $url = SteamHelper::appIdToAppUrl($entity->getAppId());
        $formDto->setUrl($url);
        $formDto->setDirectory($entity->getDirectory());

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
