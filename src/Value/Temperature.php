<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Value;

use Yaleksandr\Weather\Exception\InvalidTemperatureException;

final readonly class Temperature
{
    private function __construct(
        private float $celsius,
    ) {}

    public static function fromCelsius(float $celsius): self
    {
        if (!is_finite($celsius)) {
            throw new InvalidTemperatureException('Temperature must be finite.');
        }

        return new self($celsius);
    }

    public function celsius(): float
    {
        return $this->celsius;
    }

    public function fahrenheit(): float
    {
        return $this->celsius * 9 / 5 + 32;
    }
}
