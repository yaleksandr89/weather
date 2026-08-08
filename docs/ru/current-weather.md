# CurrentWeather

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | English | Español | 中文 | Français | Deutsch |

`Weather::current()` возвращает `CurrentWeather`. В объекте находятся координаты запроса, время наблюдения и погодные данные в единых единицах измерения.

## Поля `CurrentWeather`

| Данные | Метод | Тип | Единица | Может быть `null` |
|---|---|---|---|---|
| Координаты | `coordinates()` | `Coordinates` | градусы | Нет |
| Время наблюдения | `observedAt()` | `DateTimeImmutable` | — | Нет |
| Температура | `temperature()` | `Temperature` | °C | Нет |
| Состояние | `condition()` | `WeatherCondition` | — | Нет |
| Ощущаемая температура | `feelsLike()` | `Temperature` | °C | Да |
| Влажность | `humidityPercent()` | `float` | % | Да |
| Давление | `pressureHectopascals()` | `float` | hPa | Да |
| Ветер | `wind()` | `Wind` | m/s, градусы | Да |
| Осадки | `precipitationMillimeters()` | `float` | mm | Да |

Необязательное поле может отсутствовать в ответе конкретного провайдера. Всегда проверяйте такое значение на `null` перед использованием.

## Координаты

`Coordinates::fromDegrees($latitude, $longitude)` создаёт объект из широты и долготы в градусах. Широта принимается в диапазоне `[-90, 90]`, долгота — `[-180, 180]`.

```php
$coordinates = $current->coordinates();
echo $coordinates->latitude;
echo $coordinates->longitude;
```

## Время наблюдения

`observedAt()` возвращает `DateTimeImmutable` — время наблюдения, указанное в данных провайдера.

```php
$observedAt = $current->observedAt();
```

## Температура

`temperature()` всегда возвращает температуру в °C. `feelsLike()` возвращает `?Temperature` и может быть `null`.

```php
$celsius = $current->temperature()->celsius();
$fahrenheit = $current->temperature()->fahrenheit();
$feelsLike = $current->feelsLike()?->celsius();
```

`fahrenheit()` — дополнительное преобразование; температура в модели провайдера хранится и возвращается в °C.

## Влажность и давление

Влажность измеряется в процентах, давление — в hPa.

```php
$humidity = $current->humidityPercent();
$pressure = $current->pressureHectopascals();
```

## Ветер

`wind()` возвращает `?Wind`. Скорость всегда доступна и измеряется в m/s; направление и порыв могут быть `null`. Направление задаётся в градусах `[0, 360)`, порыв — в m/s.

```php
if (($wind = $current->wind()) !== null) {
    $speed = $wind->speedMetersPerSecond();
    $direction = $wind->directionDegrees();
    $gust = $wind->gustMetersPerSecond();
}
```

## Осадки

`precipitationMillimeters()` возвращает количество осадков в mm или `null`.

## Состояние погоды

`condition()` возвращает `WeatherCondition`. Его строковое значение (`$current->condition()->value`) может быть одним из следующих:

```text
clear
partly_cloudy
cloudy
fog
drizzle
rain
snow
sleet
thunderstorm
unknown
```

Проверку входных данных и ошибки внешнего запроса описывает [руководство по ошибкам](errors.md).
