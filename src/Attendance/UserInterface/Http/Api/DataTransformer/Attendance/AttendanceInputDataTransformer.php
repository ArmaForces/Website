<?php

declare(strict_types=1);

namespace App\Attendance\UserInterface\Http\Api\DataTransformer\Attendance;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Attendance\Domain\Model\Attendance\Attendance;
use App\Attendance\Domain\Model\Attendance\AttendanceInterface;
use App\Attendance\UserInterface\Http\Api\Input\Attendance\AttendanceInput;
use Ramsey\Uuid\Uuid;

class AttendanceInputDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function transform($object, string $to, array $context = []): Attendance
    {
        /** @var AttendanceInput $object */
        $this->validator->validate($object);

        return new Attendance(Uuid::uuid4(), $object->getMissionId(), $object->getPlayerId());
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof AttendanceInterface) {
            return false;
        }

        return Attendance::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
