<?php

declare(strict_types=1);

namespace App\Attendance\UserInterface\Http\Api\DataTransformer\Attendance;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Attendance\Domain\Model\Attendance\AttendanceInterface;
use App\Attendance\UserInterface\Http\Api\Output\Attendance\AttendanceOutput;

class AttendanceOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = []): AttendanceOutput
    {
        /** @var AttendanceInterface $object */
        $output = new AttendanceOutput();

        $output->setId($object->getId()->toString());
        $output->setCreatedAt($object->getCreatedAt());
        $output->setMissionId($object->getMissionId());
        $output->setPlayerId($object->getPlayerId());

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return AttendanceOutput::class === $to && $data instanceof AttendanceInterface;
    }
}
