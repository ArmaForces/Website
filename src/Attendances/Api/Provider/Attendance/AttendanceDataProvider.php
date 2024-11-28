<?php

declare(strict_types=1);

namespace App\Attendances\Api\Provider\Attendance;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\Operation;
use App\Attendances\Api\DataTransformer\Attendance\AttendanceOutputDataTransformer;
use App\Shared\Api\Provider\Common\AbstractDataProvider;

class AttendanceDataProvider extends AbstractDataProvider
{
    public function __construct(
        ItemProvider $itemProvider,
        CollectionProvider $collectionProvider,
        private AttendanceOutputDataTransformer $attendanceOutputDataTransformer
    ) {
        parent::__construct($itemProvider, $collectionProvider);
    }

    public function provideTransformedData(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): object {
        return $this->attendanceOutputDataTransformer->transform($data);
    }
}
