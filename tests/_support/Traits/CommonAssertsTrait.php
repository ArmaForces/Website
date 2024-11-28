<?php

declare(strict_types=1);

namespace App\Tests\Traits;

trait CommonAssertsTrait
{
    public function assertArraySame(array $expected, array $actual): void
    {
        $this->assertSame(sort($expected), sort($actual));
    }
}
