<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\Dlc;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Output\Dlc\DlcOutput;
use App\Entity\Dlc\DlcInterface;

class DlcOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = []): DlcOutput
    {
        /** @var DlcInterface $dlc */
        $dlc = $object;

        $output = new DlcOutput();

        $output->setId($dlc->getId()->toString());
        $output->setName($dlc->getName());
        $output->setCreatedAt($dlc->getCreatedAt());
        $output->setLastUpdatedAt($dlc->getLastUpdatedAt());
        $output->setAppId($dlc->getAppId());
        $output->setDirectory($dlc->getDirectory());

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return DlcOutput::class === $to && $data instanceof DlcInterface;
    }
}
