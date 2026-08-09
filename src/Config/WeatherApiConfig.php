<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Config;

use Yaleksandr\Weather\Exception\InvalidWeatherApiConfigException;

final readonly class WeatherApiConfig
{
    private string $apiKey;

    public function __construct(
        #[\SensitiveParameter]
        string $apiKey,
    ) {
        if (trim($apiKey) === '') {
            throw new InvalidWeatherApiConfigException('WeatherAPI key must not be empty.');
        }

        $this->apiKey = $apiKey;
    }

    public function apiKey(): string
    {
        return $this->apiKey;
    }

    /** @return array{apiKey: '[REDACTED]'} */
    public function __debugInfo(): array
    {
        return [
            'apiKey' => '[REDACTED]',
        ];
    }
}
