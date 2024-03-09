<?php

declare(strict_types=1);

namespace App\Shared\Test\Traits;

use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Clock\NativeClock;

trait TimeTrait
{
    public function freezeTime(string $iso8601): void
    {
        $dateTime = \DateTimeImmutable::createFromFormat(DATE_ATOM, $iso8601);
        Clock::set(new MockClock($dateTime));
    }

    public function unfreezeTime(): void
    {
        Clock::set(new NativeClock());
    }

    public function withTimeFrozenAt(string $iso8601, callable $fn): void
    {
        $this->freezeTime($iso8601);
        $fn();
        $this->unfreezeTime();
    }
}
