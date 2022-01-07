<?php

declare(strict_types=1);

namespace App\Entity\Dlc;

use App\SharedKernel\Domain\Model\AbstractBlamableEntity;
use App\SharedKernel\Domain\Model\Traits\DescribedTrait;
use App\SharedKernel\Domain\Model\Traits\NamedTrait;
use Ramsey\Uuid\UuidInterface;

class Dlc extends AbstractBlamableEntity implements DlcInterface
{
    use NamedTrait;
    use DescribedTrait;

    public function __construct(
        UuidInterface $id,
        private string $name,
        private int $appId,
        private string $directory
    ) {
        parent::__construct($id);
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
