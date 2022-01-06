<?php

declare(strict_types=1);

namespace App\Service\Version;

class VersionProvider
{
    public function __construct(
        private string $projectDir,
        private string $fileName = 'VERSION',
        private string $defaultVersion = 'dev'
    ) {
    }

    public function getVersion(): string
    {
        $filePath = "{$this->projectDir}/{$this->fileName}";

        return file_exists($filePath) ? file_get_contents($filePath) : $this->defaultVersion;
    }
}
