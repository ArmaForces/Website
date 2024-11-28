<?php

declare(strict_types=1);

namespace App\Shared\Service\Version;

interface VersionProviderInterface
{
    public function getVersion(): string;
}
