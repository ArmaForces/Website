<?php

declare(strict_types=1);

namespace App\Form\ModGroup\DataTransformer;

use App\Entity\ModGroup\ModGroup;
use App\Form\ModGroup\Dto\ModGroupFormDto;
use App\Service\IdentifierFactory\IdentifierFactoryInterface;

class ModGroupFormDtoDataTransformer
{
    public function __construct(
        private IdentifierFactoryInterface $identifierFactory
    ) {
    }

    public function transformToEntity(ModGroupFormDto $modGroupFormDto, ModGroup $modGroup = null): ModGroup
    {
        if (!$modGroup instanceof ModGroup) {
            return new ModGroup(
                $this->identifierFactory->create(),
                $modGroupFormDto->getName(),
                $modGroupFormDto->getDescription(),
                $modGroupFormDto->getMods()
            );
        }

        $modGroup->update(
            $modGroupFormDto->getName(),
            $modGroupFormDto->getDescription(),
            $modGroupFormDto->getMods()
        );

        return $modGroup;
    }

    public function transformFromEntity(ModGroupFormDto $modGroupFormDto, ModGroup $modGroup = null): ModGroupFormDto
    {
        if (!$modGroup instanceof ModGroup) {
            return $modGroupFormDto;
        }

        $modGroupFormDto->setId($modGroup->getId());
        $modGroupFormDto->setName($modGroup->getName());
        $modGroupFormDto->setDescription($modGroup->getDescription());
        $modGroupFormDto->setMods($modGroup->getMods());

        return $modGroupFormDto;
    }
}
