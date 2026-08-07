<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Value;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Exception\InvalidTemperatureException;
use Yaleksandr\Weather\Value\Temperature;

final class TemperatureTest extends TestCase
{
    #[DataProvider('celsiusValues')]
    #[TestDox('Сохраняет температуру в градусах Цельсия без изменения')]
    public function testItPreservesCanonicalCelsius(float $celsius): void
    {
        $temperature = Temperature::fromCelsius($celsius);

        self::assertSame($celsius, $temperature->celsius());
    }

    #[DataProvider('temperatureConversions')]
    #[TestDox('Преобразует градусы Цельсия в Фаренгейты')]
    public function testItConvertsCelsiusToFahrenheit(float $celsius, float $fahrenheit): void
    {
        $temperature = Temperature::fromCelsius($celsius);

        self::assertEqualsWithDelta($fahrenheit, $temperature->fahrenheit(), 0.0000001);
    }

    #[DataProvider('nonFiniteTemperatures')]
    #[TestDox('Отклоняет бесконечность и NaN')]
    public function testItRejectsNonFiniteCelsius(float $celsius): void
    {
        $this->expectException(InvalidTemperatureException::class);
        $this->expectExceptionMessageIs('Temperature must be finite.');

        Temperature::fromCelsius($celsius);
    }

    /** @return iterable<string, array{float}> */
    public static function celsiusValues(): iterable
    {
        yield 'negative' => [-12.5];
        yield 'zero' => [0.0];
        yield 'positive' => [23.75];
    }

    /** @return iterable<string, array{float, float}> */
    public static function temperatureConversions(): iterable
    {
        yield 'freezing point' => [0.0, 32.0];
        yield 'same in both scales' => [-40.0, -40.0];
        yield 'boiling point' => [100.0, 212.0];
        yield 'body temperature without rounding' => [37.0, 98.6];
    }

    /** @return iterable<string, array{float}> */
    public static function nonFiniteTemperatures(): iterable
    {
        yield 'positive infinity' => [INF];
        yield 'negative infinity' => [-INF];
        yield 'not a number' => [NAN];
    }
}
