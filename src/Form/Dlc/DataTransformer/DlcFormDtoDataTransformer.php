<?php

declare(strict_types=1);

namespace App\Form\Dlc\DataTransformer;

use App\Entity\AbstractEntity;
use App\Entity\Dlc\Dlc;
use App\Form\Dlc\Dto\DlcFormDto;
use App\Form\FormDtoInterface;
use App\Form\RegisteredDataTransformerInterface;
use App\Service\Steam\Helper\SteamHelper;
use App\Service\Steam\SteamApiClientInterface;
use Ramsey\Uuid\Uuid;

class DlcFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    public function __construct(
        private SteamApiClientInterface $steamApiClient
    ) {
    }

    /**
     * @param DlcFormDto $formDto
     * @param null|Dlc   $entity
     *
     * @return Dlc
     */
    public function transformToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): AbstractEntity
    {
        $appId = SteamHelper::appUrlToAppId($formDto->getUrl());
        $directory = $formDto->getDirectory();
        $name = $formDto->getName() ?? substr($this->steamApiClient->getAppInfo($appId)->getName(), 0, 255);

        if (!$entity instanceof Dlc) {
            return new Dlc(
                Uuid::uuid4(),
                $name,
                $formDto->getDescription(),
                $appId,
                $directory
            );
        }

        $entity->update(
            $name,
            $formDto->getDescription(),
            $appId,
            $directory
        );

        return $entity;
    }

    /**
     * @param DlcFormDto $formDto
     * @param null|Dlc   $entity
     *
     * @return DlcFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): FormDtoInterface
    {
        if (!$entity instanceof Dlc) {
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

    public function supportsTransformationToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof DlcFormDto;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof DlcFormDto;
    }
}
