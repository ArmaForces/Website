<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\Attendance;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Api\Input\Attendance\AttendanceInput;
use App\Entity\Attendance\Attendance;
use App\Entity\Attendance\AttendanceInterface;
use Ramsey\Uuid\Uuid;

class AttendanceInputDataTransformer implements DataTransformerInterface
{
    protected ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function transform($object, string $to, array $context = []): Attendance
    {
        /** @var AttendanceInput $attendanceInput */
        $attendanceInput = $object;

        $this->validator->validate($attendanceInput);

        return new Attendance(Uuid::uuid4(), $attendanceInput->getMissionId(), $attendanceInput->getPlayerId());
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof AttendanceInterface) {
            return false;
        }

        return Attendance::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
