<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Provider\WeatherApi;

use DateTimeImmutable;
use DateTimeZone;
use Yaleksandr\Weather\Exception\InvalidCurrentWeatherException;
use Yaleksandr\Weather\Exception\InvalidTemperatureException;
use Yaleksandr\Weather\Exception\InvalidWindException;
use Yaleksandr\Weather\Exception\MalformedResponseException;
use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\Temperature;
use Yaleksandr\Weather\Value\Wind;

final class WeatherApiCurrentWeatherMapper
{
    /** @param array<string, mixed> $payload */
    public static function map(array $payload, Coordinates $requestedCoordinates): CurrentWeather
    {
        $current = self::current($payload);
        $condition = self::condition($current);
        $windDirection = self::requiredNumeric($current, 'wind_degree');

        if ($windDirection === 360.0) {
            $windDirection = 0.0;
        }

        try {
            return CurrentWeather::fromObservation(
                $requestedCoordinates,
                self::observedAt(self::requiredInteger($current, 'last_updated_epoch')),
                Temperature::fromCelsius(self::requiredNumeric($current, 'temp_c')),
                WeatherApiWeatherConditionMapper::map(self::requiredInteger($condition, 'code')),
                Temperature::fromCelsius(self::requiredNumeric($current, 'feelslike_c')),
                self::requiredNumeric($current, 'humidity'),
                self::requiredNumeric($current, 'pressure_mb'),
                Wind::fromMetersPerSecond(
                    self::requiredNumeric($current, 'wind_kph') / 3.6,
                    $windDirection,
                    self::requiredNumeric($current, 'gust_kph') / 3.6,
                ),
                self::requiredNumeric($current, 'precip_mm'),
            );
        } catch (InvalidTemperatureException|InvalidWindException|InvalidCurrentWeatherException $exception) {
            throw new MalformedResponseException('WeatherAPI current weather contains invalid values.', 0, $exception);
        }
    }

    /** @param array<string, mixed> $payload
     *  @return array<array-key, mixed>
     */
    private static function current(array $payload): array
    {
        if (!array_key_exists('current', $payload) || !is_array($payload['current'])) {
            throw new MalformedResponseException('WeatherAPI response does not contain a valid current section.');
        }

        return $payload['current'];
    }

    /** @param array<array-key, mixed> $current
     *  @return array<array-key, mixed>
     */
    private static function condition(array $current): array
    {
        if (!array_key_exists('condition', $current) || !is_array($current['condition'])) {
            throw new MalformedResponseException('WeatherAPI current response does not contain a valid condition section.');
        }

        return $current['condition'];
    }

    /** @param array<array-key, mixed> $values */
    private static function requiredInteger(array $values, string $field): int
    {
        if (!array_key_exists($field, $values) || !is_int($values[$field])) {
            throw new MalformedResponseException(sprintf('WeatherAPI field "%s" must be an integer.', $field));
        }

        return $values[$field];
    }

    /** @param array<array-key, mixed> $current */
    private static function requiredNumeric(array $current, string $field): float
    {
        if (!array_key_exists($field, $current) || (!is_int($current[$field]) && !is_float($current[$field]))) {
            throw new MalformedResponseException(sprintf('WeatherAPI current field "%s" must be numeric.', $field));
        }

        return (float) $current[$field];
    }

    private static function observedAt(int $timestamp): DateTimeImmutable
    {
        try {
            return new DateTimeImmutable('@' . $timestamp)->setTimezone(new DateTimeZone('UTC'));
        } catch (\Exception $exception) {
            throw new MalformedResponseException('WeatherAPI current time is invalid.', 0, $exception);
        }
    }
}
