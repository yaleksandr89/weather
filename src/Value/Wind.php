<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Value;

use Yaleksandr\Weather\Exception\InvalidWindException;

final readonly class Wind
{
    private function __construct(
        private float $speed,
        private ?float $directionDegrees,
        private ?float $gust,
    ) {}

    public static function fromMetersPerSecond(
        float $speed,
        ?float $directionDegrees = null,
        ?float $gust = null,
    ): self {
        if (!is_finite($speed) || $speed < 0.0) {
            throw new InvalidWindException('Wind speed must be finite and non-negative.');
        }

        if ($directionDegrees !== null && (!is_finite($directionDegrees) || $directionDegrees < 0.0 || $directionDegrees >= 360.0)) {
            throw new InvalidWindException('Wind direction must be finite and in [0, 360).');
        }

        if ($gust !== null && (!is_finite($gust) || $gust < 0.0)) {
            throw new InvalidWindException('Wind gust must be finite and non-negative.');
        }

        return new self($speed, $directionDegrees, $gust);
    }

    public function speedMetersPerSecond(): float
    {
        return $this->speed;
    }

    public function directionDegrees(): ?float
    {
        return $this->directionDegrees;
    }

    public function gustMetersPerSecond(): ?float
    {
        return $this->gust;
    }
}
