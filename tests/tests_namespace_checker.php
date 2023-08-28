<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;

require_once __DIR__.'/../vendor/autoload.php';

$sourceDir = 'src';
$testDirs = [
    'tests/integration',
    'tests/unit',
];

$testFileSuffixes = [
    'Cest.php',
    'Test.php',
];

$finder = new Finder();
$finder->files()->name('*.php');
$finder->in($testDirs);

$mismatchedTestNamespaces = 0;

foreach ($finder as $testFile) {
    $testFilePath = $testFile->getPathname();
    $testSubjectFilePath = $testFilePath;

    $testSubjectFilePath = str_replace($testDirs, 'src', $testSubjectFilePath);
    $testSubjectFilePath = str_replace($testFileSuffixes, '.php', $testSubjectFilePath);

    if (!file_exists($testSubjectFilePath)) {
        echo 'Subject of test:'.PHP_EOL;
        echo $testFilePath.PHP_EOL;
        echo 'Is expected to be found in:'.PHP_EOL;
        echo $testSubjectFilePath.PHP_EOL;
        echo str_repeat('-', 100).PHP_EOL;

        ++$mismatchedTestNamespaces;
    }
}

if ($mismatchedTestNamespaces > 0) {
    exit(1);
}

exit(0);
