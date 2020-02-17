<?php

declare(strict_types=1);

namespace App\Service\Version;

class VersionProvider
{
    /** @var string */
    protected $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function getVersion(): string
    {
        $version = @file_get_contents(sprintf('%s/%s', $this->projectDir, 'VERSION'));

        return $version ?: 'dev';
    }
}
