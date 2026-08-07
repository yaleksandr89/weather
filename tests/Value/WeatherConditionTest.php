<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Value;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Value\WeatherCondition;

final class WeatherConditionTest extends TestCase
{
    #[DataProvider('conditions')]
    #[TestDox('Каждый случай имеет согласованное строковое значение')]
    public function testItHasAgreedBackingValue(WeatherCondition $condition, string $value): void
    {
        self::assertSame($value, $condition->value);
    }

    #[TestDox('Содержит ровно десять погодных состояний')]
    public function testItContainsExactlyTenCases(): void
    {
        self::assertCount(10, WeatherCondition::cases());
    }

    /** @return iterable<string, array{WeatherCondition, string}> */
    public static function conditions(): iterable
    {
        yield 'clear' => [WeatherCondition::Clear, 'clear'];
        yield 'partly cloudy' => [WeatherCondition::PartlyCloudy, 'partly_cloudy'];
        yield 'cloudy' => [WeatherCondition::Cloudy, 'cloudy'];
        yield 'fog' => [WeatherCondition::Fog, 'fog'];
        yield 'drizzle' => [WeatherCondition::Drizzle, 'drizzle'];
        yield 'rain' => [WeatherCondition::Rain, 'rain'];
        yield 'snow' => [WeatherCondition::Snow, 'snow'];
        yield 'sleet' => [WeatherCondition::Sleet, 'sleet'];
        yield 'thunderstorm' => [WeatherCondition::Thunderstorm, 'thunderstorm'];
        yield 'unknown' => [WeatherCondition::Unknown, 'unknown'];
    }
}
