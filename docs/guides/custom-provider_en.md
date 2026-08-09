# Custom provider

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./custom-provider.md) | **Selected** | [Español](./custom-provider_es.md) | [中文](./custom-provider_zh.md) | [Français](./custom-provider_fr.md) | [Deutsch](./custom-provider_de.md) |

If you need another source of current weather, implement `CurrentWeatherProvider` and pass it to `Weather`.

## CurrentWeatherProvider

The interface contract is:

```php
namespace Yaleksandr\Weather\Contract;

use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;

interface CurrentWeatherProvider
{
    public function current(Coordinates $coordinates): CurrentWeather;
}
```

## Implementation example

The provider retrieves data from its service and converts it into the public model.

```php
use Yaleksandr\Weather\Contract\CurrentWeatherProvider;
use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\Temperature;
use Yaleksandr\Weather\Value\WeatherCondition;

final class ExampleCurrentWeatherProvider implements CurrentWeatherProvider
{
    public function current(Coordinates $coordinates): CurrentWeather
    {
        // Request data from your service here and convert its response.
        return CurrentWeather::fromObservation(
            $coordinates,
            new \DateTimeImmutable(),
            Temperature::fromCelsius(20.0),
            WeatherCondition::Clear,
        );
    }
}
```

## Connecting the provider

Pass the implementation directly to the `Weather` constructor.

```php
use Yaleksandr\Weather\Weather;
use Yaleksandr\Weather\Value\Coordinates;

$customProvider = new ExampleCurrentWeatherProvider();
$weather = new Weather($customProvider);
$current = $weather->current(Coordinates::fromDegrees(55.7558, 37.6173));
```

## Provider responsibilities

- retrieve data from its source;
- convert it to `CurrentWeather`;
- respect the units and constraints of the public model;
- handle integration errors correctly.

The result structure is documented in the [`CurrentWeather` guide](../reference/current-weather_en.md), and the error model in the [error guide](../reference/errors_en.md).
