<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Value;

use Yaleksandr\Weather\Exception\InvalidCoordinatesException;

final readonly class Coordinates
{
    private function __construct(
        public float $latitude,
        public float $longitude,
    ) {}

    public static function fromDegrees(float $latitude, float $longitude): self
    {
        if (!is_finite($latitude) || $latitude < -90.0 || $latitude > 90.0) {
            throw new InvalidCoordinatesException('Invalid latitude.');
        }

        if (!is_finite($longitude) || $longitude < -180.0 || $longitude > 180.0) {
            throw new InvalidCoordinatesException('Invalid longitude.');
        }

        return new self($latitude, $longitude);
    }
}
