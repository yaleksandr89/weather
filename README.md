# Weather

[![CI](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml/badge.svg)](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml)

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | English | Español | 中文 | Français | Deutsch |

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

Для первого запуска проще использовать **Open-Meteo** — для него не требуется API key.

### Open-Meteo

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

### WeatherAPI

Для WeatherAPI нужен API key. Храните его вне исходного кода, например в переменной окружения:

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$apiKey = getenv('WEATHER_API_KEY')
    ?: throw new \RuntimeException('WEATHER_API_KEY is not set.');

$weather = Weather::create(
    new WeatherApiConfig($apiKey),
);

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);
```

Настройка встроенных сервисов и различия между ними подробно описаны в [руководстве по провайдерам](docs/ru/providers.md).

## От ответа сервиса к CurrentWeather

Внешние API используют собственные названия полей и форматы. Например, Open-Meteo вернул для запроса с координатами `55.7558, 37.6173` такой фрагмент `current`:

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

`Weather::current()` возвращает типизированный `CurrentWeather`; значения доступны через публичные методы:

```php
$temperature = $current->temperature()->celsius(); // 21.8
$condition = $current->condition()->value; // clear
$humidity = $current->humidityPercent(); // 48.0
$pressure = $current->pressureHectopascals(); // 1018.4
$windSpeed = $current->wind()?->speedMetersPerSecond(); // 2.91
```

`Temperature` и `Wind` — типизированные объекты, а `WeatherCondition` — enum. Прикладному коду не нужно знать поля провайдера вроде `temperature_2m` или `weather_code`.

## Работа с результатом

`current()` возвращает `CurrentWeather` с температурой, состоянием погоды, временем наблюдения и доступными дополнительными данными:

```php
$temperature = $current->temperature()->celsius();
$condition = $current->condition()->value;
$humidity = $current->humidityPercent();
$pressure = $current->pressureHectopascals();
$wind = $current->wind();
```

Полный состав данных, единицы измерения и необязательные значения описаны в [руководстве по `CurrentWeather`](docs/ru/current-weather.md).

## Обработка ошибок

Ошибки входных данных отделены от ошибок, возникающих при обращении к погодному сервису.

Правила обработки и иерархия исключений описаны в [руководстве по ошибкам](docs/ru/errors.md).

## Собственный провайдер

Если WeatherAPI и Open-Meteo не подходят, можно реализовать `CurrentWeatherProvider` и передать его напрямую:

```php
use Yaleksandr\Weather\Weather;

$weather = new Weather($customProvider);
```

Контракт и пример реализации приведены в [руководстве по собственному провайдеру](docs/ru/custom-provider.md).

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
