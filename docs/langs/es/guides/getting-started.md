# Primeros pasos

## Elige un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../ru/guides/getting-started.md) | [English](../../en/guides/getting-started.md) | **Seleccionado** | [中文](../../zh/guides/getting-started.md) | [Français](../../fr/guides/getting-started.md) | [Deutsch](../../de/guides/getting-started.md) |

Aquí se muestran la instalación, la elección de un proveedor integrado, la creación de coordenadas y la primera consulta del tiempo actual.

## Requisitos

Se necesitan `PHP ^8.4` y `Composer`.

## Instalación

```bash
composer require yaleksandr89/weather
```

## Elección de proveedor

Lo más sencillo es empezar con Open-Meteo: la integración incluida no requiere API key. En ambos casos, crea `Weather` mediante `Weather::create()`.

### Open-Meteo

`OpenMeteoConfig` no recibe parámetros.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());
```

### WeatherAPI

`WeatherApiConfig` requiere una API key.

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(
    new WeatherApiConfig('YOUR_WEATHERAPI_KEY'),
);
```

Sustituye `YOUR_WEATHERAPI_KEY` por tu clave y no añadas una clave real al repositorio. El almacenamiento es responsabilidad de la aplicación; usa, por ejemplo, la configuración de la aplicación, variables de entorno o un almacén de secretos.

La comparación detallada y los enlaces a la documentación de los servicios están en la [guía de proveedores](providers.md).

## Coordenadas

Crea `Coordinates` a partir de latitud y longitud en grados. La latitud debe estar en `[-90, 90]`, la longitud en `[-180, 180]`; ambos valores deben ser números finitos.

```php
use Yaleksandr\Weather\Value\Coordinates;

$coordinates = Coordinates::fromDegrees(55.7558, 37.6173);
```

## Obtener el tiempo actual

Pasa las coordenadas a `current()`.

```php
$current = $weather->current($coordinates);

echo $current->temperature()->celsius();
echo $current->condition()->value;
```

La composición del resultado y los valores opcionales se describen en la [guía de `CurrentWeather`](../reference/current-weather.md). Los errores de datos de entrada y de consulta al servicio se explican en la [guía de errores](../reference/errors.md).
