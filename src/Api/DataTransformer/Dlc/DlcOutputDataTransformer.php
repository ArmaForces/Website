<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\Dlc;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Api\Output\Dlc\DlcOutput;
use App\Entity\Dlc\Dlc;

class DlcOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = []): DlcOutput
    {
        /** @var Dlc $object */
        $output = new DlcOutput();

        $output->setId($object->getId()->toString());
        $output->setName($object->getName());
        $output->setCreatedAt($object->getCreatedAt());
        $output->setLastUpdatedAt($object->getLastUpdatedAt());
        $output->setAppId($object->getAppId());
        $output->setDirectory($object->getDirectory());

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return DlcOutput::class === $to && $data instanceof Dlc;
    }
}
