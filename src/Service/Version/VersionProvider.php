<?php

declare(strict_types=1);

namespace App\Service\Version;

class VersionProvider
{
    protected string $filePath;
    protected string $defaultVersion;

    public function __construct(string $projectDir, string $fileName = 'VERSION', string $defaultVersion = 'dev')
    {
        $this->filePath = "{$projectDir}/{$fileName}";
        $this->defaultVersion = $defaultVersion;
    }

    public function getVersion(): string
    {
        if (!file_exists($this->filePath)) {
            return $this->defaultVersion;
        }

        return file_get_contents($this->filePath);
    }
}
