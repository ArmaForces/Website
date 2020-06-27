<?php

declare(strict_types=1);

namespace App\Api\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Dto\ModListDetailsOutput;
use App\Api\Dto\ModListOutput;
use App\Api\Dto\ModOutput;
use App\Entity\ModList\ModList;

class ModListDetailsOutputDataTransformer implements DataTransformerInterface
{
    /** @var ModOutputDataTransformer */
    protected $modDataTransformer;

    public function __construct(ModOutputDataTransformer $modDataTransformer)
    {
        $this->modDataTransformer = $modDataTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @var ModList
     */
    public function transform($modList, string $to, array $context = []): ModListOutput
    {
        $output = new ModListDetailsOutput();

        $output->setId($modList->getId());
        $output->setName($modList->getName());
        $output->setCreatedAt($modList->getCreatedAt());
        $output->setLastUpdatedAt($modList->getLastUpdatedAt());

        $mods = [];
        foreach ($modList->getMods() as $mod) {
            $mods[] = $this->modDataTransformer->transform($mod, ModOutput::class, $context);
        }
        $output->setMods($mods);

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ModListDetailsOutput::class === $to && $data instanceof ModList;
    }
}
