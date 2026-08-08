<?php

declare(strict_types=1);

namespace Yaleksandr\Weather;

use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Contract\CurrentWeatherProvider;
use Yaleksandr\Weather\Internal\ProviderFactory;
use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;

final readonly class Weather
{
    public function __construct(
        private CurrentWeatherProvider $provider,
    ) {}

    public static function create(
        WeatherApiConfig|OpenMeteoConfig $config,
    ): self {
        return new self(ProviderFactory::create($config));
    }

    public function current(Coordinates $coordinates): CurrentWeather
    {
        return $this->provider->current($coordinates);
    }
}
