<?php

declare(strict_types=1);

namespace App\Service\Version;

interface VersionProviderInterface
{
    public function getVersion(): string;
}
