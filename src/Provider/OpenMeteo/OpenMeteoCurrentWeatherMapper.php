<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Provider\OpenMeteo;

use DateTimeImmutable;
use Yaleksandr\Weather\Exception\InvalidCurrentWeatherException;
use Yaleksandr\Weather\Exception\InvalidTemperatureException;
use Yaleksandr\Weather\Exception\InvalidWindException;
use Yaleksandr\Weather\Exception\MalformedResponseException;
use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\Temperature;
use Yaleksandr\Weather\Value\Wind;

final class OpenMeteoCurrentWeatherMapper
{
    /** @param array<string, mixed> $payload */
    public static function map(array $payload, Coordinates $requestedCoordinates): CurrentWeather
    {
        $current = self::current($payload);
        $timestamp = self::requiredInteger($current, 'time');
        $windDirection = self::requiredNumeric($current, 'wind_direction_10m');

        if ($windDirection === 360.0) {
            $windDirection = 0.0;
        }

        try {
            return CurrentWeather::fromObservation(
                $requestedCoordinates,
                self::observedAt($timestamp),
                Temperature::fromCelsius(self::requiredNumeric($current, 'temperature_2m')),
                OpenMeteoWeatherConditionMapper::map(self::requiredInteger($current, 'weather_code')),
                Temperature::fromCelsius(self::requiredNumeric($current, 'apparent_temperature')),
                self::requiredNumeric($current, 'relative_humidity_2m'),
                self::requiredNumeric($current, 'pressure_msl'),
                Wind::fromMetersPerSecond(
                    self::requiredNumeric($current, 'wind_speed_10m'),
                    $windDirection,
                    self::requiredNumeric($current, 'wind_gusts_10m'),
                ),
                self::requiredNumeric($current, 'precipitation'),
            );
        } catch (InvalidTemperatureException|InvalidWindException|InvalidCurrentWeatherException $exception) {
            throw new MalformedResponseException('Open-Meteo current weather contains invalid values.', 0, $exception);
        }
    }

    /** @param array<string, mixed> $payload
     *  @return array<array-key, mixed>
     */
    private static function current(array $payload): array
    {
        if (!array_key_exists('current', $payload) || !is_array($payload['current'])) {
            throw new MalformedResponseException('Open-Meteo response does not contain a valid current section.');
        }

        return $payload['current'];
    }

    /** @param array<array-key, mixed> $current */
    private static function requiredInteger(array $current, string $field): int
    {
        if (!array_key_exists($field, $current) || !is_int($current[$field])) {
            throw new MalformedResponseException(sprintf('Open-Meteo current field "%s" must be an integer.', $field));
        }

        return $current[$field];
    }

    /** @param array<array-key, mixed> $current */
    private static function requiredNumeric(array $current, string $field): float
    {
        if (!array_key_exists($field, $current) || (!is_int($current[$field]) && !is_float($current[$field]))) {
            throw new MalformedResponseException(sprintf('Open-Meteo current field "%s" must be numeric.', $field));
        }

        return (float) $current[$field];
    }

    private static function observedAt(int $timestamp): DateTimeImmutable
    {
        return DateTimeImmutable::createFromTimestamp($timestamp);
    }
}
