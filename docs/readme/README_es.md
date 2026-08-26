# Weather

[![Source Code](https://img.shields.io/badge/source-yaleksandr89%2Fweather-blue.svg?style=flat-square)](https://github.com/yaleksandr89/weather)
[![Latest Stable Version](https://img.shields.io/packagist/v/yaleksandr89/weather.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/weather)
[![Total Downloads](https://img.shields.io/packagist/dt/yaleksandr89/weather.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/weather)
[![PHP](https://img.shields.io/badge/PHP-%5E8.4-777BB4.svg?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![guzzlehttp/guzzle](https://img.shields.io/badge/guzzlehttp%2Fguzzle-%5E8.0.1-4E5D94.svg?style=flat-square)](https://packagist.org/packages/guzzlehttp/guzzle)
[![CI](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml/badge.svg)](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](../../LICENSE)

<p align="center">
  <img
    src="../assets/weather-readme-cover.png"
    alt="Weather — current weather PHP package with unified provider normalization"
    width="100%"
  >
</p>

## Elige un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../README.md) | [English](./README_en.md) | **Seleccionado** | [中文](./README_zh.md) | [Français](./README_fr.md) | [Deutsch](./README_de.md) |

Biblioteca para obtener el tiempo actual por coordenadas mediante WeatherAPI y Open-Meteo.
Los datos de distintos servicios se normalizan a un único formato tipado y a unidades comunes.

## Para qué sirve el paquete

WeatherAPI y Open-Meteo usan API HTTP y formatos de respuesta distintos. Weather ofrece una única API de PHP para obtener el tiempo actual por coordenadas, por lo que el código de la aplicación no necesita conocer las particularidades de cada servicio.

## Qué hace el paquete

- obtiene el tiempo actual por latitud y longitud;
- admite WeatherAPI y Open-Meteo;
- devuelve el resultado como `CurrentWeather`;
- normaliza los datos meteorológicos a unidades métricas comunes;
- ofrece estados meteorológicos tipados mediante `WeatherCondition`;
- permite conectar una fuente de datos propia mediante `CurrentWeatherProvider`.

## Requisitos

- `PHP ^8.4`;
- `Composer`.

## Inicio rápido

Instala el paquete:

```bash
composer require yaleksandr89/weather
```

### Open-Meteo

Open-Meteo no requiere API key.

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
<summary>Mostrar la respuesta real de Open-Meteo y CurrentWeather</summary>

#### Fragmento de una respuesta real de Open-Meteo

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

#### Resultado tras la normalización

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

WeatherAPI requiere una API key:

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

Sustituye `YOUR_WEATHERAPI_KEY` por tu clave. No añadas una clave real al repositorio; guárdala fuera del código fuente, por ejemplo en la configuración de la aplicación, variables de entorno o un almacén de secretos.

<details>
<summary>Mostrar la respuesta real de WeatherAPI y CurrentWeather</summary>

#### Fragmento de una respuesta real de WeatherAPI

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

#### Resultado tras la normalización

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

Ambos proveedores devuelven `CurrentWeather`. La composición completa de los datos, las unidades, los valores opcionales y los ejemplos de uso se describen en la [guía de `CurrentWeather`](../reference/current-weather_es.md). La configuración de los servicios integrados y sus diferencias se explican en la [guía de proveedores](../guides/providers_es.md).

## Gestión de errores

Los errores de entrada se mantienen separados de los errores que se producen al consultar un servicio meteorológico.

Las reglas de gestión y la jerarquía de excepciones se describen en la [guía de errores](../reference/errors_es.md).

## Proveedor propio

Si WeatherAPI y Open-Meteo no se ajustan a tus necesidades, puedes implementar `CurrentWeatherProvider` y pasarlo directamente:

```php
use Yaleksandr\Weather\Weather;

$weather = new Weather($customProvider);
```

El contrato y un ejemplo de implementación están en la [guía del proveedor propio](../guides/custom-provider_es.md).

## Limitaciones

- solo se admite el tiempo actual;
- las coordenadas deben conocerse de antemano;
- no se realiza búsqueda de ciudades ni geocodificación;
- no hay cambio automático entre proveedores;
- no se realizan retry ni cache automáticos.

## Comentarios

- errores reproducibles — [GitHub Issues](https://github.com/yaleksandr89/weather/issues).

---

<p align="center">
  Si el paquete te resulta útil, dale una estrella en GitHub: ayudará a que otros desarrolladores lo encuentren. 🤘
</p>
