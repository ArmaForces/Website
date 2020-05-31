<?php

declare(strict_types=1);

namespace App\Api\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Dto\ModListOutput;
use App\Api\Dto\ModOutput;
use App\Entity\ModList\ModList;

class ModListOutputDataTransformer implements DataTransformerInterface
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
        $output = new ModListOutput();

        $output->id = $modList->getId();
        $output->name = $modList->getName();
        $output->createdAt = $modList->getCreatedAt();
        $output->lastUpdatedAt = $modList->getLastUpdatedAt();

        $output->mods = [];
        foreach ($modList->getMods() as $mod) {
            $output->mods[] = $this->modDataTransformer->transform($mod, ModOutput::class, $context);
        }

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
