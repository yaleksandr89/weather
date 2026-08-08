# Провайдеры

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | English | Español | 中文 | Français | Deutsch |

Встроены WeatherAPI и Open-Meteo. При создании `Weather` выберите соответствующую конфигурацию; затем работа через `current()` будет одинаковой.

## Open-Meteo

`OpenMeteoConfig` не принимает параметров. Для использования через текущую реализацию API key не нужен.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());
$current = $weather->current(Coordinates::fromDegrees(55.7558, 37.6176));
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
$current = $weather->current(Coordinates::fromDegrees(55.7558, 37.6176));
```

## Как выбрать провайдера

| Провайдер | Конфигурация | Нужен API key |
|---|---|---|
| Open-Meteo | `OpenMeteoConfig` | Не требуется |
| WeatherAPI | `WeatherApiConfig` | Обязателен |

Оба варианта возвращают `CurrentWeather`. Состав результата и единицы измерения описаны в [руководстве по `CurrentWeather`](current-weather.md).

## Официальная документация

- [WeatherAPI](https://www.weatherapi.com/docs/)
- [Open-Meteo](https://open-meteo.com/en/docs)

Если нужен другой источник, реализуйте [собственный провайдер](custom-provider.md).
