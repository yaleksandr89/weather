# Провайдеры

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | English | Español | 中文 | Français | Deutsch |

Встроены WeatherAPI и Open-Meteo. При создании `Weather` выберите соответствующую конфигурацию; затем вызов `current()` будет одинаковым. Оба варианта возвращают `CurrentWeather`, а детали ответов конкретного сервиса приводятся к этой модели внутри библиотеки.

## Open-Meteo

`OpenMeteoConfig` не принимает параметров. В текущей встроенной интеграции API key не нужен.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());
$current = $weather->current(Coordinates::fromDegrees(55.7558, 37.6173));
```

## WeatherAPI

`WeatherApiConfig` требует непустой API key. Получайте его из окружения и не добавляйте ключ в код, репозиторий или логи.

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$apiKey = getenv('WEATHER_API_KEY')
    ?: throw new \RuntimeException('WEATHER_API_KEY is not set.');
$weather = Weather::create(new WeatherApiConfig($apiKey));
$current = $weather->current(Coordinates::fromDegrees(55.7558, 37.6173));
```

## Как выбрать провайдера

| Провайдер | Конфигурация | Нужен API key |
|---|---|---|
| Open-Meteo | `OpenMeteoConfig` | Не требуется |
| WeatherAPI | `WeatherApiConfig` | Обязателен |

Состав результата и единицы измерения описаны в [руководстве по `CurrentWeather`](current-weather.md). Короткий пример того, как ответ Open-Meteo становится этой моделью, есть в [README](../../README.md#от-ответа-сервиса-к-currentweather).

## Официальная документация

- [WeatherAPI](https://www.weatherapi.com/docs/)
- [Open-Meteo](https://open-meteo.com/en/docs)

Если нужен другой источник, реализуйте [собственный провайдер](custom-provider.md).
