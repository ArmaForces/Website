<?php

declare(strict_types=1);

namespace App\Entity\Dlc;

use App\Entity\BlamableEntityInterface;
use App\Entity\Traits\DescribedInterface;
use App\Entity\Traits\NamedInterface;

interface DlcInterface extends BlamableEntityInterface, NamedInterface, DescribedInterface
{
    public function getAppId(): int;

    public function setAppId(int $appId): void;

    public function getDirectory(): string;

    public function setDirectory(string $directory): void;
}
