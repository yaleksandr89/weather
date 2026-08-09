# CurrentWeather

## Elige un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./current-weather.md) | [English](./current-weather_en.md) | **Seleccionado** | [中文](./current-weather_zh.md) | [Français](./current-weather_fr.md) | [Deutsch](./current-weather_de.md) |

`Weather::current()` devuelve `CurrentWeather`. Este modelo tipado contiene las coordenadas de la solicitud, la hora de observación y los datos meteorológicos normalizados.

## Campos de `CurrentWeather`

| Dato | Método | Tipo | Unidad | Puede ser `null` |
|---|---|---|---|---|
| Coordenadas | `coordinates()` | `Coordinates` | grados | No |
| Hora de observación | `observedAt()` | `DateTimeImmutable` | — | No |
| Temperatura | `temperature()` | `Temperature` | °C | No |
| Estado | `condition()` | `WeatherCondition` | — | No |
| Sensación térmica | `feelsLike()` | `Temperature` | °C | Sí |
| Humedad | `humidityPercent()` | `float` | % | Sí |
| Presión | `pressureHectopascals()` | `float` | hPa | Sí |
| Viento | `wind()` | `Wind` | m/s, grados | Sí |
| Precipitación | `precipitationMillimeters()` | `float` | mm | Sí |

Un campo opcional puede faltar en la respuesta de un proveedor. Comprueba `null` antes de usarlo.

## Coordenadas

`Coordinates::fromDegrees($latitude, $longitude)` crea un objeto a partir de latitud y longitud en grados. La latitud debe estar en `[-90, 90]`, la longitud en `[-180, 180]`; ambos valores deben ser números finitos.

```php
$coordinates = $current->coordinates();
echo $coordinates->latitude;
echo $coordinates->longitude;
```

## Hora de observación

`observedAt()` devuelve un `DateTimeImmutable` con la hora de observación indicada por el proveedor.

```php
$observedAt = $current->observedAt();
```

## Temperatura

`temperature()` siempre devuelve `Temperature` en °C. `feelsLike()` devuelve `?Temperature` y puede ser `null`.

```php
$celsius = $current->temperature()->celsius();
$fahrenheit = $current->temperature()->fahrenheit();
$feelsLike = $current->feelsLike()?->celsius();
```

`fahrenheit()` realiza una conversión adicional; el valor base de `Temperature` se almacena en °C.

## Humedad y presión

La humedad se mide en porcentaje y la presión en hPa.

```php
$humidity = $current->humidityPercent();
$pressure = $current->pressureHectopascals();
```

## Viento

`wind()` devuelve `?Wind`. Si hay datos de viento, la velocidad se mide en m/s; la dirección y la ráfaga pueden ser `null`. La dirección se expresa en grados `[0, 360)` y la ráfaga en m/s.

```php
if (($wind = $current->wind()) !== null) {
    $speed = $wind->speedMetersPerSecond();
    $direction = $wind->directionDegrees();
    $gust = $wind->gustMetersPerSecond();
}
```

## Precipitación

`precipitationMillimeters()` devuelve la precipitación en mm o `null`.

## Estado del tiempo

`condition()` devuelve el enum `WeatherCondition`. Su valor de cadena (`$current->condition()->value`) corresponde a uno de estos casos del enum:

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

La validación de entrada y los errores de solicitudes externas se describen en la [guía de errores](errors_es.md).
