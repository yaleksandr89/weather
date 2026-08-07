<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Value;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Exception\InvalidWindException;
use Yaleksandr\Weather\Value\Wind;

final class WindTest extends TestCase
{
    #[TestDox('Сохраняет скорость, направление и порыв ветра в канонических единицах')]
    public function testItPreservesSpeedDirectionAndGust(): void
    {
        $wind = Wind::fromMetersPerSecond(4.5, 270.0, 7.25);

        self::assertSame(4.5, $wind->speedMetersPerSecond());
        self::assertSame(270.0, $wind->directionDegrees());
        self::assertSame(7.25, $wind->gustMetersPerSecond());
    }

    #[TestDox('Допускает отсутствующие направление и порыв ветра')]
    public function testItAllowsAbsentDirectionAndGust(): void
    {
        $wind = Wind::fromMetersPerSecond(0.0);

        self::assertNull($wind->directionDegrees());
        self::assertNull($wind->gustMetersPerSecond());
    }

    #[DataProvider('validDirectionBoundaries')]
    #[TestDox('Принимает граничные значения направления ветра')]
    public function testItAcceptsDirectionBoundaries(float $directionDegrees): void
    {
        $wind = Wind::fromMetersPerSecond(1.0, $directionDegrees);

        self::assertSame($directionDegrees, $wind->directionDegrees());
    }

    #[DataProvider('invalidSpeeds')]
    #[TestDox('Отклоняет некорректную скорость ветра')]
    public function testItRejectsInvalidSpeed(float $speed): void
    {
        $this->expectException(InvalidWindException::class);

        Wind::fromMetersPerSecond($speed);
    }

    #[DataProvider('invalidDirections')]
    #[TestDox('Отклоняет некорректное направление ветра')]
    public function testItRejectsInvalidDirection(float $directionDegrees): void
    {
        $this->expectException(InvalidWindException::class);

        Wind::fromMetersPerSecond(1.0, $directionDegrees);
    }

    #[DataProvider('invalidGusts')]
    #[TestDox('Отклоняет некорректный порыв ветра')]
    public function testItRejectsInvalidGust(float $gust): void
    {
        $this->expectException(InvalidWindException::class);

        Wind::fromMetersPerSecond(1.0, null, $gust);
    }

    /** @return iterable<string, array{float}> */
    public static function validDirectionBoundaries(): iterable
    {
        yield 'north' => [0.0];
        yield 'just below full circle' => [359.999999];
    }

    /** @return iterable<string, array{float}> */
    public static function invalidSpeeds(): iterable
    {
        yield 'negative' => [-0.1];
        yield 'positive infinity' => [INF];
        yield 'negative infinity' => [-INF];
        yield 'not a number' => [NAN];
    }

    /** @return iterable<string, array{float}> */
    public static function invalidDirections(): iterable
    {
        yield 'negative' => [-0.1];
        yield 'full circle' => [360.0];
        yield 'above full circle' => [360.1];
        yield 'positive infinity' => [INF];
        yield 'negative infinity' => [-INF];
        yield 'not a number' => [NAN];
    }

    /** @return iterable<string, array{float}> */
    public static function invalidGusts(): iterable
    {
        yield 'negative' => [-0.1];
        yield 'positive infinity' => [INF];
        yield 'negative infinity' => [-INF];
        yield 'not a number' => [NAN];
    }
}
