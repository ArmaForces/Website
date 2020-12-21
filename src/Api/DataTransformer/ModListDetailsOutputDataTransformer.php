<?php

declare(strict_types=1);

namespace App\Api\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Dto\ModListDetailsOutput;
use App\Api\Dto\ModListOutput;
use App\Api\Dto\ModOutput;
use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\Repository\ModListRepository;

class ModListDetailsOutputDataTransformer implements DataTransformerInterface
{
    /** @var ModOutputDataTransformer */
    protected $modDataTransformer;

    /** @var ModListRepository */
    protected $modListRepository;

    public function __construct(
        ModOutputDataTransformer $modDataTransformer,
        ModListRepository $modListRepository
    ) {
        $this->modDataTransformer = $modDataTransformer;
        $this->modListRepository = $modListRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @param ModListInterface $modList
     */
    public function transform($modList, string $to, array $context = []): ModListOutput
    {
        $output = new ModListDetailsOutput();

        $output->setId($modList->getId());
        $output->setName($modList->getName());
        $output->setCreatedAt($modList->getCreatedAt());
        $output->setLastUpdatedAt($modList->getLastUpdatedAt());

        $mods = [];
        foreach ($this->modListRepository->findIncludedMods($modList) as $mod) {
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
