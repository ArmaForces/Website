<?php

declare(strict_types=1);

namespace App\Entity\Dlc;

use App\Entity\DescribedEntityInterface;

interface DlcInterface extends DescribedEntityInterface
{
    public function getAppId(): int;

    public function setAppId(int $appId): void;

    public function getDirectory(): string;

    public function setDirectory(string $directory): void;
}
