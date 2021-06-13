<?php

declare(strict_types=1);

namespace App\Api\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Dto\DlcOutput;
use App\Entity\Dlc\DlcInterface;

class DlcOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param DlcInterface $dlc
     */
    public function transform($dlc, string $to, array $context = []): DlcOutput
    {
        $output = new DlcOutput();

        $output->setId($dlc->getId()->toString());
        $output->setName($dlc->getName());
        $output->setCreatedAt($dlc->getCreatedAt());
        $output->setLastUpdatedAt($dlc->getLastUpdatedAt());
        $output->setAppId($dlc->getAppId());

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return DlcOutput::class === $to && $data instanceof DlcInterface;
    }
}
