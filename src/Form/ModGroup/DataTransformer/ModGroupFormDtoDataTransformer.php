<?php

declare(strict_types=1);

namespace App\Form\ModGroup\DataTransformer;

use App\Entity\ModGroup\ModGroup;
use App\Form\ModGroup\Dto\ModGroupFormDto;
use Ramsey\Uuid\Uuid;

class ModGroupFormDtoDataTransformer
{
    public function transformToEntity(ModGroupFormDto $modGroupFormDto, ModGroup $modGroup = null): ModGroup
    {
        if (!$modGroup instanceof ModGroup) {
            return new ModGroup(
                Uuid::uuid4(),
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
