# Провайдеры

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | [English](./providers_en.md) | [Español](./providers_es.md) | [中文](./providers_zh.md) | [Français](./providers_fr.md) | [Deutsch](./providers_de.md) |

Встроены WeatherAPI и Open-Meteo. При создании `Weather` выберите соответствующую конфигурацию; затем вызов `current()` будет одинаковым. Оба варианта возвращают `CurrentWeather`, а особенности формата, единиц измерения и кодов конкретного сервиса нормализуются внутри библиотеки.

## Open-Meteo

`OpenMeteoConfig` не принимает параметров. В текущей встроенной интеграции API key не нужен.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);
```

### Реальный ответ и нормализация

Фрагмент реального ответа Open-Meteo:

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

После преобразования библиотека возвращает:

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

Open-Meteo уже возвращает скорость и порывы ветра в m/s, поэтому эти значения переходят в `Wind` без смены единицы измерения. Unix timestamp из `time` становится `DateTimeImmutable` в UTC, `weather_code` преобразуется в `WeatherCondition`, а координатами результата остаются координаты исходного запроса.

## WeatherAPI

`WeatherApiConfig` требует непустой API key.

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

Замените `YOUR_WEATHERAPI_KEY` своим ключом и не добавляйте реальный ключ в репозиторий или логи. Способ хранения — ответственность приложения: используйте, например, конфигурацию приложения, переменные окружения или хранилище секретов.

### Реальный ответ и нормализация

Фрагмент реального ответа WeatherAPI:

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

После преобразования библиотека возвращает:

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

WeatherAPI отдаёт ветер в km/h, а публичная модель использует m/s: `12.6 km/h` превращается в `3.5 m/s`, а `15.8 km/h` — в `4.3888888888889 m/s`. `condition.code` преобразуется в `WeatherCondition` (`1000` → `clear`), `last_updated_epoch` становится временем наблюдения в UTC, а `pressure_mb` используется как давление в hPa. Как и для Open-Meteo, `CurrentWeather` сохраняет координаты исходного запроса.

## Как выбрать провайдера

| Провайдер | Конфигурация | Нужен API key |
|---|---|---|
| Open-Meteo | `OpenMeteoConfig` | Не требуется |
| WeatherAPI | `WeatherApiConfig` | Обязателен |

Оба встроенных провайдера возвращают одну публичную модель. Полный состав результата, единицы измерения и необязательные значения описаны в [руководстве по `CurrentWeather`](../reference/current-weather.md).

## Официальная документация

- [WeatherAPI](https://www.weatherapi.com/docs/)
- [Open-Meteo](https://open-meteo.com/en/docs)

Если нужен другой источник, реализуйте [собственный провайдер](custom-provider.md).
