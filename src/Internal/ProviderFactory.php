<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Internal;

use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Contract\CurrentWeatherProvider;
use Yaleksandr\Weather\Internal\Http\DefaultHttpDependenciesFactory;
use Yaleksandr\Weather\Provider\OpenMeteo\OpenMeteoProvider;
use Yaleksandr\Weather\Provider\WeatherApi\WeatherApiProvider;

final class ProviderFactory
{
    public static function create(
        WeatherApiConfig|OpenMeteoConfig $config,
    ): CurrentWeatherProvider {
        $http = DefaultHttpDependenciesFactory::create();

        if ($config instanceof WeatherApiConfig) {
            return new WeatherApiProvider(
                $config,
                $http->client(),
                $http->requestFactory(),
                $http->uriFactory(),
            );
        }

        return new OpenMeteoProvider(
            $http->client(),
            $http->requestFactory(),
            $http->uriFactory(),
        );
    }
}
