# Собственный провайдер

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | English | Español | 中文 | Français | Deutsch |

Если нужен другой источник текущей погоды, реализуйте `CurrentWeatherProvider` и передайте его в `Weather`.

## CurrentWeatherProvider

Интерфейс принимает `Coordinates` и возвращает `CurrentWeather`.

```php
use Yaleksandr\Weather\Contract\CurrentWeatherProvider;
use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;

interface CurrentWeatherProvider
{
    public function current(Coordinates $coordinates): CurrentWeather;
}
```

## Пример реализации

Провайдер получает данные своего сервиса и преобразует их в публичную модель.

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
        // Здесь выполните запрос к своему сервису и преобразуйте его ответ.
        return CurrentWeather::fromObservation(
            $coordinates,
            new \DateTimeImmutable(),
            Temperature::fromCelsius(20.0),
            WeatherCondition::Clear,
        );
    }
}
```

## Подключение

Передайте реализацию прямо в конструктор `Weather`.

```php
use Yaleksandr\Weather\Weather;

$customProvider = new ExampleCurrentWeatherProvider();
$weather = new Weather($customProvider);
$current = $weather->current(Coordinates::fromDegrees(55.7558, 37.6176));
```

## Ответственность провайдера

Провайдер должен:

- получить данные своего источника;
- преобразовать их в `CurrentWeather`;
- соблюдать единицы измерения и ограничения публичной модели;
- корректно обрабатывать ошибки своей интеграции.

Структура результата описана в [руководстве по `CurrentWeather`](current-weather.md), а модель ошибок — в [руководстве по ошибкам](errors.md).
