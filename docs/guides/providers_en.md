# Providers

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./providers.md) | **Selected** | [Español](./providers_es.md) | [中文](./providers_zh.md) | [Français](./providers_fr.md) | [Deutsch](./providers_de.md) |

WeatherAPI and Open-Meteo are built in. Choose the matching configuration when creating `Weather`; after that, the `current()` call is the same. Both return `CurrentWeather`, while service-specific formats, units, and condition codes are normalized inside the library.

## Open-Meteo

`OpenMeteoConfig` takes no parameters. The built-in integration does not require an API key.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);
```

### Real response and normalization

Real Open-Meteo response fragment:

```json
{
    "time": 1786285800,
    "interval": 900,
    "temperature_2m": 21.8,
    "relative_humidity_2m": 48,
    "apparent_temperature": 20.4,
    "precipitation": 0.0,
    "weather_code": 1,
    "pressure_msl": 1018.4,
    "wind_speed_10m": 2.91,
    "wind_direction_10m": 333,
    "wind_gusts_10m": 8.1
}
```

After normalization, the library returns:

```text
Yaleksandr\Weather\Model\CurrentWeather {
    coordinates: Yaleksandr\Weather\Value\Coordinates {
        latitude: 55.7558
        longitude: 37.6173
    }
    observedAt: DateTimeImmutable {
        date: 2026-08-09 14:30:00 UTC
    }
    temperature: Yaleksandr\Weather\Value\Temperature {
        celsius: 21.8
    }
    condition: Yaleksandr\Weather\Value\WeatherCondition {
        name: Clear
        value: clear
    }
    feelsLike: Yaleksandr\Weather\Value\Temperature {
        celsius: 20.4
    }
    humidityPercent: 48.0
    pressureHectopascals: 1018.4
    wind: Yaleksandr\Weather\Value\Wind {
        speed: 2.91
        directionDegrees: 333.0
        gust: 8.1
    }
    precipitationMillimeters: 0.0
}
```

Open-Meteo already returns wind speed and gusts in m/s, so those values are placed into `Wind` without unit conversion. The Unix timestamp in `time` becomes a UTC `DateTimeImmutable`, `weather_code` is mapped to `WeatherCondition`, and the result keeps the coordinates from the original request.

## WeatherAPI

`WeatherApiConfig` requires a non-empty API key.

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(
    new WeatherApiConfig('YOUR_WEATHERAPI_KEY'),
);

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);
```

Replace `YOUR_WEATHERAPI_KEY` with your key and do not commit a real key to the repository or logs. Key storage is the application's responsibility; use application configuration, environment variables, or a secret store.

### Real response and normalization

Real WeatherAPI response fragment:

```json
{
    "last_updated_epoch": 1786290300,
    "temp_c": 21.4,
    "condition": {
        "text": "Sunny",
        "code": 1000
    },
    "wind_kph": 12.6,
    "wind_degree": 341,
    "pressure_mb": 1019.0,
    "precip_mm": 0.0,
    "humidity": 41,
    "feelslike_c": 18.4,
    "gust_kph": 15.8
}
```

After normalization, the library returns:

```text
Yaleksandr\Weather\Model\CurrentWeather {
    coordinates: Yaleksandr\Weather\Value\Coordinates {
        latitude: 55.7558
        longitude: 37.6173
    }
    observedAt: DateTimeImmutable {
        date: 2026-08-09 15:45:00 UTC
    }
    temperature: Yaleksandr\Weather\Value\Temperature {
        celsius: 21.4
    }
    condition: Yaleksandr\Weather\Value\WeatherCondition {
        name: Clear
        value: clear
    }
    feelsLike: Yaleksandr\Weather\Value\Temperature {
        celsius: 18.4
    }
    humidityPercent: 41.0
    pressureHectopascals: 1019.0
    wind: Yaleksandr\Weather\Value\Wind {
        speed: 3.5
        directionDegrees: 341.0
        gust: 4.3888888888889
    }
    precipitationMillimeters: 0.0
}
```

WeatherAPI returns wind values in km/h, while the public model uses m/s: `12.6 km/h` becomes `3.5 m/s`, and `15.8 km/h` becomes `4.3888888888889 m/s`. `condition.code` is mapped to `WeatherCondition` (`1000` → `clear`), `last_updated_epoch` becomes the observation time in UTC, and `pressure_mb` is used as pressure in hPa. As with Open-Meteo, `CurrentWeather` keeps the coordinates from the original request.

## Choosing a provider

| Provider | Configuration | API key required |
|---|---|---|
| Open-Meteo | `OpenMeteoConfig` | No |
| WeatherAPI | `WeatherApiConfig` | Yes |

Both built-in providers return the same public model. The complete result structure, units, and optional values are documented in the [`CurrentWeather` guide](../reference/current-weather_en.md).

## Official documentation

- [WeatherAPI](https://www.weatherapi.com/docs/)
- [Open-Meteo](https://open-meteo.com/en/docs)

If you need another data source, implement a [custom provider](custom-provider_en.md).
