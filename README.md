# Weather

[![Source Code](https://img.shields.io/badge/source-yaleksandr89%2Fweather-blue.svg?style=flat-square)](https://github.com/yaleksandr89/weather)
[![CI](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml/badge.svg)](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml)
[![Codecov](https://codecov.io/gh/yaleksandr89/weather/graph/badge.svg)](https://codecov.io/gh/yaleksandr89/weather)
[![Latest Stable Version](https://img.shields.io/packagist/v/yaleksandr89/weather.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/weather)
[![Total Downloads](https://img.shields.io/packagist/dt/yaleksandr89/weather.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/weather)
[![PHP](https://img.shields.io/badge/PHP-8.4%2B-777BB4.svg?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![guzzlehttp/guzzle](https://img.shields.io/badge/guzzlehttp%2Fguzzle-%5E8.0.1-4E5D94.svg?style=flat-square)](https://packagist.org/packages/guzzlehttp/guzzle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

<p align="center">
  <img
    src="docs/assets/weather-readme-cover.png"
    alt="Weather — current weather PHP package with unified provider normalization"
    width="100%"
  >
</p>

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | [English](./docs/readme/README_en.md) | [Español](./docs/readme/README_es.md) | [中文](./docs/readme/README_zh.md) | [Français](./docs/readme/README_fr.md) | [Deutsch](./docs/readme/README_de.md) |

Библиотека для получения текущей погоды по координатам через WeatherAPI и Open-Meteo.
Данные разных сервисов приводятся к единому типизированному формату и общим единицам измерения.

## Для чего нужен пакет

WeatherAPI и Open-Meteo используют разные HTTP API и форматы ответов. Weather даёт единый PHP API для получения текущей погоды по координатам, поэтому прикладному коду не нужно учитывать особенности конкретного сервиса.

## Что делает пакет

- получает текущую погоду по широте и долготе;
- поддерживает WeatherAPI и Open-Meteo;
- возвращает результат в виде `CurrentWeather`;
- приводит погодные данные к единым метрическим единицам;
- предоставляет типизированные состояния погоды через `WeatherCondition`;
- позволяет подключить собственный источник данных через `CurrentWeatherProvider`.

## Требования

- `PHP ^8.4`;
- `Composer`.

## Быстрый старт

Установите пакет:

```bash
composer require yaleksandr89/weather
```

### Open-Meteo

Для Open-Meteo API key не требуется.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);

echo $current->temperature()->celsius();
echo $current->condition()->value;
```

<details>
<summary>Показать реальный ответ Open-Meteo и CurrentWeather</summary>

#### Фрагмент реального ответа Open-Meteo

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

#### Результат после преобразования

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

</details>

### WeatherAPI

Для WeatherAPI нужен API key:

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

Замените `YOUR_WEATHERAPI_KEY` своим ключом. Не добавляйте реальный ключ в репозиторий: в приложении храните его вне исходного кода, например в конфигурации приложения, переменных окружения или хранилище секретов.

<details>
<summary>Показать реальный ответ WeatherAPI и CurrentWeather</summary>

#### Фрагмент реального ответа WeatherAPI

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

#### Результат после преобразования

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

</details>

Оба провайдера возвращают `CurrentWeather`. Полный состав данных, единицы измерения, необязательные значения и примеры работы с объектом описаны в [руководстве по `CurrentWeather`](docs/reference/current-weather.md). Настройка встроенных сервисов и различия между ними — в [руководстве по провайдерам](docs/guides/providers.md).

## Обработка ошибок

Ошибки входных данных отделены от ошибок, возникающих при обращении к погодному сервису.

Правила обработки и иерархия исключений описаны в [руководстве по ошибкам](docs/reference/errors.md).

## Собственный провайдер

Если WeatherAPI и Open-Meteo не подходят, можно реализовать `CurrentWeatherProvider` и передать его напрямую:

```php
use Yaleksandr\Weather\Weather;

$weather = new Weather($customProvider);
```

Контракт и пример реализации приведены в [руководстве по собственному провайдеру](docs/guides/custom-provider.md).

## Ограничения

- поддерживается только текущая погода;
- координаты должны быть известны заранее;
- поиск города и геокодирование не выполняются;
- автоматического переключения между провайдерами нет;
- автоматические retry и cache не выполняются.

## Обратная связь

- воспроизводимые ошибки — [GitHub Issues](https://github.com/yaleksandr89/weather/issues).

---

<p align="center">
  Если пакет оказался полезен, поставьте звезду на GitHub — так его будет проще найти другим разработчикам. 🤘
</p>
