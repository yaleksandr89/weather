# Proveedores

## Elige un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./providers.md) | [English](./providers_en.md) | **Seleccionado** | [中文](./providers_zh.md) | [Français](./providers_fr.md) | [Deutsch](./providers_de.md) |

WeatherAPI y Open-Meteo vienen integrados. Al crear `Weather`, elige la configuración correspondiente; después, la llamada a `current()` es la misma. Ambos devuelven `CurrentWeather`, mientras que los formatos, unidades y códigos propios de cada servicio se normalizan dentro de la biblioteca.

## Open-Meteo

`OpenMeteoConfig` no recibe parámetros. La integración incluida no requiere API key.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);
```

### Respuesta real y normalización

Fragmento de una respuesta real de Open-Meteo:

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

Tras la normalización, la biblioteca devuelve:

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

Open-Meteo ya devuelve la velocidad y las ráfagas de viento en m/s, por lo que esos valores pasan a `Wind` sin conversión de unidades. El Unix timestamp de `time` se convierte en `DateTimeImmutable` en UTC, `weather_code` se transforma en `WeatherCondition` y el resultado conserva las coordenadas de la solicitud original.

## WeatherAPI

`WeatherApiConfig` requiere una API key no vacía.

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

Sustituye `YOUR_WEATHERAPI_KEY` por tu clave y no añadas una clave real al repositorio ni a los logs. El almacenamiento es responsabilidad de la aplicación; usa, por ejemplo, la configuración de la aplicación, variables de entorno o un almacén de secretos.

### Respuesta real y normalización

Fragmento de una respuesta real de WeatherAPI:

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

Tras la normalización, la biblioteca devuelve:

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

WeatherAPI devuelve el viento en km/h, mientras que el modelo público usa m/s: `12.6 km/h` se convierte en `3.5 m/s` y `15.8 km/h` en `4.3888888888889 m/s`. `condition.code` se transforma en `WeatherCondition` (`1000` → `clear`), `last_updated_epoch` se convierte en la hora de observación en UTC y `pressure_mb` se usa como presión en hPa. Igual que con Open-Meteo, `CurrentWeather` conserva las coordenadas de la solicitud original.

## Cómo elegir proveedor

| Proveedor | Configuración | Requiere API key |
|---|---|---|
| Open-Meteo | `OpenMeteoConfig` | No |
| WeatherAPI | `WeatherApiConfig` | Sí |

Ambos proveedores integrados devuelven el mismo modelo público. La composición completa del resultado, las unidades y los valores opcionales se describen en la [guía de `CurrentWeather`](../reference/current-weather_es.md).

## Documentación oficial

- [WeatherAPI](https://www.weatherapi.com/docs/)
- [Open-Meteo](https://open-meteo.com/en/docs)

Si necesitas otra fuente de datos, implementa un [proveedor propio](custom-provider_es.md).
