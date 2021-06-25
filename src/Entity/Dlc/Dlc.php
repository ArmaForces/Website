<?php

declare(strict_types=1);

namespace App\Entity\Dlc;

use App\Entity\AbstractDescribedEntity;
use Ramsey\Uuid\UuidInterface;

class Dlc extends AbstractDescribedEntity implements DlcInterface
{
    protected int $appId;
    protected string $directory;

    public function __construct(UuidInterface $id, string $name, int $appId, string $directory)
    {
        parent::__construct($id, $name);

        $this->appId = $appId;
        $this->directory = $directory;
    }

    public function getAppId(): int
    {
        return $this->appId;
    }

    public function setAppId(int $appId): void
    {
        $this->appId = $appId;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }
}
