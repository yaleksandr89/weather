# Начало работы

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | [English](./getting-started_en.md) | [Español](./getting-started_es.md) | [中文](./getting-started_zh.md) | [Français](./getting-started_fr.md) | [Deutsch](./getting-started_de.md) |

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

`WeatherApiConfig` требует API key.

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(
    new WeatherApiConfig('YOUR_WEATHERAPI_KEY'),
);
```

Замените `YOUR_WEATHERAPI_KEY` своим ключом и не добавляйте реальный ключ в репозиторий. Способ хранения — ответственность приложения: используйте, например, конфигурацию приложения, переменные окружения или хранилище секретов.

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

Подробнее о составе результата и необязательных значениях — в [руководстве по `CurrentWeather`](../reference/current-weather.md). Ошибки входных данных и запросов к сервису описаны в [руководстве по ошибкам](../reference/errors.md).
