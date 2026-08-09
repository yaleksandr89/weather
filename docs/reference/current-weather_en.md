# CurrentWeather

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./current-weather.md) | **Selected** | [Español](./current-weather_es.md) | [中文](./current-weather_zh.md) | [Français](./current-weather_fr.md) | [Deutsch](./current-weather_de.md) |

`Weather::current()` returns `CurrentWeather`. This typed model contains the request coordinates, observation time, and normalized weather data.

## `CurrentWeather` fields

| Data | Method | Type | Unit | May be `null` |
|---|---|---|---|---|
| Coordinates | `coordinates()` | `Coordinates` | degrees | No |
| Observation time | `observedAt()` | `DateTimeImmutable` | — | No |
| Temperature | `temperature()` | `Temperature` | °C | No |
| Condition | `condition()` | `WeatherCondition` | — | No |
| Feels-like temperature | `feelsLike()` | `Temperature` | °C | Yes |
| Humidity | `humidityPercent()` | `float` | % | Yes |
| Pressure | `pressureHectopascals()` | `float` | hPa | Yes |
| Wind | `wind()` | `Wind` | m/s, degrees | Yes |
| Precipitation | `precipitationMillimeters()` | `float` | mm | Yes |

An optional field may be absent from a provider response. Check it for `null` before use.

## Coordinates

`Coordinates::fromDegrees($latitude, $longitude)` creates an object from latitude and longitude in degrees. Latitude must be in `[-90, 90]`, longitude in `[-180, 180]`; both values must be finite numbers.

```php
$coordinates = $current->coordinates();
echo $coordinates->latitude;
echo $coordinates->longitude;
```

## Observation time

`observedAt()` returns a `DateTimeImmutable` representing the observation time reported by the provider.

```php
$observedAt = $current->observedAt();
```

## Temperature

`temperature()` always returns `Temperature` in °C. `feelsLike()` returns `?Temperature` and may be `null`.

```php
$celsius = $current->temperature()->celsius();
$fahrenheit = $current->temperature()->fahrenheit();
$feelsLike = $current->feelsLike()?->celsius();
```

`fahrenheit()` performs an additional conversion; the base `Temperature` value is stored in °C.

## Humidity and pressure

Humidity is measured in percent and pressure in hPa.

```php
$humidity = $current->humidityPercent();
$pressure = $current->pressureHectopascals();
```

## Wind

`wind()` returns `?Wind`. When wind data is available, speed is measured in m/s; direction and gust may be `null`. Direction is expressed in degrees `[0, 360)`, and gust in m/s.

```php
if (($wind = $current->wind()) !== null) {
    $speed = $wind->speedMetersPerSecond();
    $direction = $wind->directionDegrees();
    $gust = $wind->gustMetersPerSecond();
}
```

## Precipitation

`precipitationMillimeters()` returns precipitation in mm or `null`.

## Weather condition

`condition()` returns the `WeatherCondition` enum. Its string value (`$current->condition()->value`) is one of these enum cases:

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

Input validation and external-request errors are described in the [error guide](errors_en.md).
