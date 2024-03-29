<?php

declare(strict_types=1);

namespace App\Mods\Api\DataTransformer\Dlc;

use App\Mods\Api\Output\Dlc\DlcOutput;
use App\Mods\Entity\Dlc\Dlc;

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
