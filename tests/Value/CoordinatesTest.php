<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Value;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Exception\InvalidCoordinatesException;
use Yaleksandr\Weather\Value\Coordinates;

final class CoordinatesTest extends TestCase
{
    #[TestDox('Сохраняет корректные координаты без изменения')]
    public function testItPreservesValidCoordinates(): void
    {
        $coordinates = Coordinates::fromDegrees(55.7558, 37.6173);

        self::assertSame(55.7558, $coordinates->latitude);
        self::assertSame(37.6173, $coordinates->longitude);
    }

    #[DataProvider('latitudeBoundaries')]
    #[TestDox('Принимает граничные значения широты')]
    public function testItAcceptsLatitudeBoundaries(float $latitude): void
    {
        $coordinates = Coordinates::fromDegrees($latitude, 0.0);

        self::assertSame($latitude, $coordinates->latitude);
    }

    #[DataProvider('longitudeBoundaries')]
    #[TestDox('Принимает граничные значения долготы')]
    public function testItAcceptsLongitudeBoundaries(float $longitude): void
    {
        $coordinates = Coordinates::fromDegrees(0.0, $longitude);

        self::assertSame($longitude, $coordinates->longitude);
    }

    #[DataProvider('invalidLatitudes')]
    #[TestDox('Отклоняет некорректную широту')]
    public function testItRejectsInvalidLatitudes(float $latitude): void
    {
        $this->expectException(InvalidCoordinatesException::class);
        $this->expectExceptionMessageIsOrContains('latitude');

        Coordinates::fromDegrees($latitude, 0.0);
    }

    #[DataProvider('invalidLongitudes')]
    #[TestDox('Отклоняет некорректную долготу')]
    public function testItRejectsInvalidLongitudes(float $longitude): void
    {
        $this->expectException(InvalidCoordinatesException::class);
        $this->expectExceptionMessageIsOrContains('longitude');

        Coordinates::fromDegrees(0.0, $longitude);
    }

    /** @return iterable<string, array{float}> */
    public static function latitudeBoundaries(): iterable
    {
        yield 'minimum' => [-90.0];
        yield 'maximum' => [90.0];
    }

    /** @return iterable<string, array{float}> */
    public static function longitudeBoundaries(): iterable
    {
        yield 'minimum' => [-180.0];
        yield 'maximum' => [180.0];
    }

    /** @return iterable<string, array{float}> */
    public static function invalidLatitudes(): iterable
    {
        yield 'below minimum' => [-90.1];
        yield 'above maximum' => [90.1];
        yield 'positive infinity' => [INF];
        yield 'negative infinity' => [-INF];
        yield 'not a number' => [NAN];
    }

    /** @return iterable<string, array{float}> */
    public static function invalidLongitudes(): iterable
    {
        yield 'below minimum' => [-180.1];
        yield 'above maximum' => [180.1];
        yield 'positive infinity' => [INF];
        yield 'negative infinity' => [-INF];
        yield 'not a number' => [NAN];
    }
}
