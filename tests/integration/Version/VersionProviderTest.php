<?php

declare(strict_types=1);

namespace App\Tests\Integration\Version;

use App\Service\Version\VersionProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \App\Service\Version\VersionProvider
 */
final class VersionProviderTest extends TestCase
{
    /**
     * @test
     */
    public function getVersion_versionFileExist_returnsVersion(): void
    {
        $fileContent = '1.0.0';
        $fileName = 'VERSION';
        $fileDir = sys_get_temp_dir();
        $filePath = "{$fileDir}/{$fileName}";
        file_put_contents($filePath, $fileContent);

        $versionProvider = new VersionProvider($fileDir);
        $version = $versionProvider->getVersion();

        static::assertSame($fileContent, $version);

        unlink($filePath);
    }

    /**
     * @test
     */
    public function getVersion_versionFileNotExist_returnsDefaultVersion(): void
    {
        $defaultVersion = 'dev';
        $fileName = 'some_non_existing_file';
        $fileDir = sys_get_temp_dir();

        $versionProvider = new VersionProvider($fileDir, $fileName, $defaultVersion);
        $version = $versionProvider->getVersion();

        static::assertSame($defaultVersion, $version);
    }
}
