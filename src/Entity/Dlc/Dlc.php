<?php

declare(strict_types=1);

namespace App\Entity\Dlc;

use App\Entity\AbstractBlamableEntity;
use App\Entity\Traits\DescribedTrait;
use App\Entity\Traits\NamedTrait;
use Ramsey\Uuid\UuidInterface;

class Dlc extends AbstractBlamableEntity implements DlcInterface
{
    use NamedTrait;
    use DescribedTrait;

    protected int $appId;
    protected string $directory;

    public function __construct(UuidInterface $id, string $name, int $appId, string $directory)
    {
        parent::__construct($id);

        $this->name = $name;
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
