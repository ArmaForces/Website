<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\Dlc;

use App\Api\Output\Dlc\DlcOutput;
use App\Entity\Dlc\Dlc;

class DlcOutputDataTransformer
{
    public function transform(Dlc $dlc): DlcOutput
    {
        return new DlcOutput(
            $dlc->getId()->toString(),
            $dlc->getName(),
            $dlc->getAppId(),
            $dlc->getDirectory(),
            $dlc->getCreatedAt(),
            $dlc->getLastUpdatedAt()
        );
    }
}
