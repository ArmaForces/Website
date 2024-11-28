<?php

declare(strict_types=1);

namespace App\Mods\Form\ModList\External\DataTransformer;

use App\Mods\Entity\ModList\ExternalModList;
use App\Mods\Form\ModList\External\Dto\ExternalModListFormDto;
use App\Shared\Service\IdentifierFactory\IdentifierFactoryInterface;

class ExternalModListFormDtoDataTransformer
{
    public function __construct(
        private IdentifierFactoryInterface $identifierFactory
    ) {
    }

    public function transformToEntity(ExternalModListFormDto $externalModListFormDto, ExternalModList $externalModList = null): ExternalModList
    {
        if (!$externalModList instanceof ExternalModList) {
            return new ExternalModList(
                $this->identifierFactory->create(),
                $externalModListFormDto->getName(),
                $externalModListFormDto->getDescription(),
                $externalModListFormDto->getUrl(),
                $externalModListFormDto->isActive(),
            );
        }

        $externalModList->update(
            $externalModListFormDto->getName(),
            $externalModListFormDto->getDescription(),
            $externalModListFormDto->getUrl(),
            $externalModListFormDto->isActive(),
        );

        return $externalModList;
    }

    public function transformFromEntity(ExternalModListFormDto $externalModListFormDto, ExternalModList $externalModList = null): ExternalModListFormDto
    {
        if (!$externalModList instanceof ExternalModList) {
            return $externalModListFormDto;
        }

        $externalModListFormDto->setId($externalModList->getId());
        $externalModListFormDto->setName($externalModList->getName());
        $externalModListFormDto->setDescription($externalModList->getDescription());
        $externalModListFormDto->setUrl($externalModList->getUrl());
        $externalModListFormDto->setActive($externalModList->isActive());

        return $externalModListFormDto;
    }
}
