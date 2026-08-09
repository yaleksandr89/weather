<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Model;

use DateTimeImmutable;
use Yaleksandr\Weather\Exception\InvalidCurrentWeatherException;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\Temperature;
use Yaleksandr\Weather\Value\WeatherCondition;
use Yaleksandr\Weather\Value\Wind;

final readonly class CurrentWeather
{
    private function __construct(
        private Coordinates $coordinates,
        private DateTimeImmutable $observedAt,
        private Temperature $temperature,
        private WeatherCondition $condition,
        private ?Temperature $feelsLike,
        private ?float $humidityPercent,
        private ?float $pressureHectopascals,
        private ?Wind $wind,
        private ?float $precipitationMillimeters,
    ) {}

    public static function fromObservation(
        Coordinates $coordinates,
        DateTimeImmutable $observedAt,
        Temperature $temperature,
        WeatherCondition $condition,
        ?Temperature $feelsLike = null,
        ?float $humidityPercent = null,
        ?float $pressureHectopascals = null,
        ?Wind $wind = null,
        ?float $precipitationMillimeters = null,
    ): self {
        if ($humidityPercent !== null && (!is_finite($humidityPercent) || $humidityPercent < 0.0 || $humidityPercent > 100.0)) {
            throw new InvalidCurrentWeatherException('Humidity must be finite and between 0 and 100 percent.');
        }

        if ($pressureHectopascals !== null && (!is_finite($pressureHectopascals) || $pressureHectopascals <= 0.0)) {
            throw new InvalidCurrentWeatherException('Pressure must be finite and greater than zero.');
        }

        if ($precipitationMillimeters !== null && (!is_finite($precipitationMillimeters) || $precipitationMillimeters < 0.0)) {
            throw new InvalidCurrentWeatherException('Precipitation must be finite and non-negative.');
        }

        return new self(
            $coordinates,
            $observedAt,
            $temperature,
            $condition,
            $feelsLike,
            $humidityPercent,
            $pressureHectopascals,
            $wind,
            $precipitationMillimeters,
        );
    }

    public function coordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function observedAt(): DateTimeImmutable
    {
        return $this->observedAt;
    }

    public function temperature(): Temperature
    {
        return $this->temperature;
    }

    public function condition(): WeatherCondition
    {
        return $this->condition;
    }

    public function feelsLike(): ?Temperature
    {
        return $this->feelsLike;
    }

    public function humidityPercent(): ?float
    {
        return $this->humidityPercent;
    }

    public function pressureHectopascals(): ?float
    {
        return $this->pressureHectopascals;
    }

    public function wind(): ?Wind
    {
        return $this->wind;
    }

    public function precipitationMillimeters(): ?float
    {
        return $this->precipitationMillimeters;
    }
}
