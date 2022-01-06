<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\ModList;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Output\ModList\ModListOutput;
use App\Entity\ModList\ModList;

class ModListOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param ModList $modList
     */
    public function transform($modList, string $to, array $context = []): ModListOutput
    {
        $output = new ModListOutput();

        $output->setId($modList->getId()->toString());
        $output->setName($modList->getName());
        $output->setActive($modList->isActive());
        $output->setApproved($modList->isApproved());
        $output->setCreatedAt($modList->getCreatedAt());
        $output->setLastUpdatedAt($modList->getLastUpdatedAt());

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ModListOutput::class === $to && $data instanceof ModList;
    }
}
