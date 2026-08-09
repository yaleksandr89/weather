<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Config;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Exception\InvalidWeatherApiConfigException;

final class WeatherApiConfigTest extends TestCase
{
    #[TestDox('Сохраняет корректный ключ WeatherAPI без изменения')]
    public function testItPreservesValidApiKey(): void
    {
        $apiKey = '  weather-api-key  ';

        $config = new WeatherApiConfig(apiKey: $apiKey);

        self::assertSame($apiKey, $config->apiKey());
    }

    #[DataProvider('invalidApiKeys')]
    #[TestDox('Отклоняет пустой ключ WeatherAPI')]
    public function testItRejectsEmptyApiKey(string $apiKey): void
    {
        $this->expectException(InvalidWeatherApiConfigException::class);

        new WeatherApiConfig(apiKey: $apiKey);
    }

    #[TestDox('Скрывает ключ WeatherAPI в отладочной информации')]
    public function testItRedactsApiKeyFromDebugInfo(): void
    {
        $config = new WeatherApiConfig(apiKey: 'secret-weather-api-key');

        self::assertSame(['apiKey' => '[REDACTED]'], $config->__debugInfo());
    }

    /** @return iterable<string, array{string}> */
    public static function invalidApiKeys(): iterable
    {
        yield 'empty' => [''];
        yield 'spaces' => ['   '];
        yield 'tabs and newlines' => ["\t\n"];
    }
}
