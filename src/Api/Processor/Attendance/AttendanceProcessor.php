<?php

declare(strict_types=1);

namespace App\Api\Processor\Attendance;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Validator\ValidatorInterface;
use App\Api\DataTransformer\Attendance\AttendanceInputDataTransformer;
use App\Api\DataTransformer\Attendance\AttendanceOutputDataTransformer;
use App\Api\Input\Attendance\AttendanceInput;

class AttendanceProcessor implements ProcessorInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private AttendanceInputDataTransformer $attendanceInputDataTransformer,
        private AttendanceOutputDataTransformer $attendanceOutputDataTransformer,
        private PersistProcessor $persistProcessor,
    ) {
    }

    /**
     * @param AttendanceInput $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        $this->validator->validate($data);

        $attendance = $this->attendanceInputDataTransformer->transform($data);

        $this->persistProcessor->process($attendance, $operation, $uriVariables, $context);

        return $this->attendanceOutputDataTransformer->transform($attendance);
    }
}
