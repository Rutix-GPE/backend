<?php

namespace App\Service\Clock;

interface ClockInterface
{
    public function now(): \DateTimeImmutable;
}