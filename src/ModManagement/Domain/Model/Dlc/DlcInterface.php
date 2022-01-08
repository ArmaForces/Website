<?php

declare(strict_types=1);

namespace App\ModManagement\Domain\Model\Dlc;

use App\SharedKernel\Domain\Model\BlamableEntityInterface;
use App\SharedKernel\Domain\Model\Traits\DescribedInterface;
use App\SharedKernel\Domain\Model\Traits\NamedInterface;

interface DlcInterface extends BlamableEntityInterface, NamedInterface, DescribedInterface
{
    public function getAppId(): int;

    public function setAppId(int $appId): void;

    public function getDirectory(): string;

    public function setDirectory(string $directory): void;
}
