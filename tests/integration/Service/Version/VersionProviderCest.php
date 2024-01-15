<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Version;

use App\Service\Version\VersionProvider;
use App\Tests\IntegrationTester;

final class VersionProviderCest
{
    public function testGetVersionNumberWhenFileExist(IntegrationTester $I): void
    {
        $fileContent = '1.0.0';
        $fileName = 'VERSION';
        $fileDir = sys_get_temp_dir();
        $filePath = "{$fileDir}/{$fileName}";
        file_put_contents($filePath, $fileContent);

        $versionProvider = new VersionProvider($fileDir);
        $version = $versionProvider->getVersion();

        $I->assertSame($fileContent, $version);

        unlink($filePath);
    }

    public function testGetDefaultVersionNumberWhenFileNotExist(IntegrationTester $I): void
    {
        $defaultVersion = 'dev';
        $fileName = 'some_non_existing_file';
        $fileDir = sys_get_temp_dir();

        $versionProvider = new VersionProvider($fileDir, $fileName, $defaultVersion);
        $version = $versionProvider->getVersion();

        $I->assertSame($defaultVersion, $version);
    }
}
