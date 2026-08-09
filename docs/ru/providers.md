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

`WeatherApiConfig` требует непустой API key.

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(
    new WeatherApiConfig('YOUR_WEATHERAPI_KEY'),
);
$current = $weather->current(Coordinates::fromDegrees(55.7558, 37.6173));
```

Замените `YOUR_WEATHERAPI_KEY` своим ключом и не добавляйте реальный ключ в репозиторий или логи. Способ хранения — ответственность приложения: используйте, например, конфигурацию приложения, переменные окружения или хранилище секретов.

## Как выбрать провайдера

| Провайдер | Конфигурация | Нужен API key |
|---|---|---|
| Open-Meteo | `OpenMeteoConfig` | Не требуется |
| WeatherAPI | `WeatherApiConfig` | Обязателен |

Состав результата и единицы измерения описаны в [руководстве по `CurrentWeather`](current-weather.md). Короткие примеры запуска и преобразования ответов обоих провайдеров есть в [README](../../README.md#быстрый-старт).

## Официальная документация

- [WeatherAPI](https://www.weatherapi.com/docs/)
- [Open-Meteo](https://open-meteo.com/en/docs)

Если нужен другой источник, реализуйте [собственный провайдер](custom-provider.md).
