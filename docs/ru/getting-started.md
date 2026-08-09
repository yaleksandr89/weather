# Начало работы

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | English | Español | 中文 | Français | Deutsch |

Здесь показаны установка, выбор встроенного провайдера, создание координат и первый запрос текущей погоды.

## Требования

Нужны `PHP ^8.4` и `Composer`.

## Установка

```bash
composer require yaleksandr89/weather
```

## Выбор провайдера

Проще начать с Open-Meteo: в текущей встроенной интеграции API key для него не нужен. Для обоих вариантов создайте `Weather` через `Weather::create()`.

### Open-Meteo

`OpenMeteoConfig` не требует параметров.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());
```

### WeatherAPI

`WeatherApiConfig` требует API key. Получайте его из окружения и не храните в исходном коде.

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Weather;

$apiKey = getenv('WEATHER_API_KEY')
    ?: throw new \RuntimeException('WEATHER_API_KEY is not set.');
$weather = Weather::create(new WeatherApiConfig($apiKey));
```

Подробное сравнение и ссылки на документацию сервисов есть в [руководстве по провайдерам](providers.md).

## Координаты

Создайте `Coordinates` из широты и долготы в градусах. Широта должна находиться в диапазоне `[-90, 90]`, долгота — в `[-180, 180]`; оба значения должны быть конечными числами.

```php
use Yaleksandr\Weather\Value\Coordinates;

$coordinates = Coordinates::fromDegrees(55.7558, 37.6173);
```

## Получение текущей погоды

Передайте координаты методу `current()`.

```php
$current = $weather->current($coordinates);

echo $current->temperature()->celsius();
echo $current->condition()->value;
```

Подробнее о составе результата и необязательных значениях — в [руководстве по `CurrentWeather`](current-weather.md). Ошибки входных данных и запросов к сервису описаны в [руководстве по ошибкам](errors.md).
