<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\Clock\ClockInterface;
use App\Service\Clock\SystemClock;

class SystemClockTest extends TestCase
{
    public function testSystemClockImplementsClockInterface()
    {
        $clock = new SystemClock();
        $this->assertInstanceOf(ClockInterface::class, $clock);
    }

    public function testNowReturnsDateTimeImmutable()
    {
        $clock = new SystemClock();
        $now = $clock->now();
        $this->assertInstanceOf(\DateTimeImmutable::class, $now);

        // Optionnel : on vérifie que la date retournée est très proche de maintenant (tolérance 2 secondes)
        $nowTimestamp = $now->getTimestamp();
        $currentTimestamp = (new \DateTimeImmutable())->getTimestamp();

        $this->assertTrue(abs($currentTimestamp - $nowTimestamp) < 3, "The timestamp is not close to current time.");
    }
}
