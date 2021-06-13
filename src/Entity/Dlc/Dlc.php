<?php

declare(strict_types=1);

namespace App\Entity\Dlc;

use App\Entity\AbstractDescribedEntity;
use Ramsey\Uuid\UuidInterface;

class Dlc extends AbstractDescribedEntity implements DlcInterface
{
    private int $appId;

    public function __construct(UuidInterface $id, string $name, int $appId)
    {
        parent::__construct($id, $name);

        $this->appId = $appId;
    }

    public function getAppId(): int
    {
        return $this->appId;
    }

    public function setAppId(int $appId): void
    {
        $this->appId = $appId;
    }
}
