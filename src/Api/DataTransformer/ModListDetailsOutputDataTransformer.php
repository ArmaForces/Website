<?php

declare(strict_types=1);

namespace App\Api\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Dto\ModListDetailsOutput;
use App\Api\Dto\ModListOutput;
use App\Api\Dto\ModOutput;
use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\Repository\Mod\ModRepository;

class ModListDetailsOutputDataTransformer implements DataTransformerInterface
{
    protected ModOutputDataTransformer $modDataTransformer;
    protected ModRepository $modRepository;

    public function __construct(
        ModOutputDataTransformer $modDataTransformer,
        ModRepository $modRepository
    ) {
        $this->modDataTransformer = $modDataTransformer;
        $this->modRepository = $modRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @param ModListInterface $modList
     */
    public function transform($modList, string $to, array $context = []): ModListOutput
    {
        $output = new ModListDetailsOutput();

        $output->setId($modList->getId()->toString());
        $output->setName($modList->getName());
        $output->setCreatedAt($modList->getCreatedAt());
        $output->setLastUpdatedAt($modList->getLastUpdatedAt());

        $mods = [];
        foreach ($this->modRepository->findIncludedMods($modList) as $mod) {
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
