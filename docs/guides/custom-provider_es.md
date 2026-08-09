# Proveedor propio

## Elige un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./custom-provider.md) | [English](./custom-provider_en.md) | **Seleccionado** | [中文](./custom-provider_zh.md) | [Français](./custom-provider_fr.md) | [Deutsch](./custom-provider_de.md) |

Si necesitas otra fuente de tiempo actual, implementa `CurrentWeatherProvider` y pásalo a `Weather`.

## CurrentWeatherProvider

El contrato de la interfaz es:

```php
namespace Yaleksandr\Weather\Contract;

use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;

interface CurrentWeatherProvider
{
    public function current(Coordinates $coordinates): CurrentWeather;
}
```

## Ejemplo de implementación

El proveedor obtiene los datos de su servicio y los convierte al modelo público.

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
        // Solicita aquí los datos a tu servicio y convierte su respuesta.
        return CurrentWeather::fromObservation(
            $coordinates,
            new \DateTimeImmutable(),
            Temperature::fromCelsius(20.0),
            WeatherCondition::Clear,
        );
    }
}
```

## Conexión

Pasa la implementación directamente al constructor de `Weather`.

```php
use Yaleksandr\Weather\Weather;
use Yaleksandr\Weather\Value\Coordinates;

$customProvider = new ExampleCurrentWeatherProvider();
$weather = new Weather($customProvider);
$current = $weather->current(Coordinates::fromDegrees(55.7558, 37.6173));
```

## Responsabilidades del proveedor

- obtener los datos de su fuente;
- convertirlos a `CurrentWeather`;
- respetar las unidades y restricciones del modelo público;
- gestionar correctamente los errores de la integración.

La estructura del resultado se describe en la [guía de `CurrentWeather`](../reference/current-weather_es.md) y el modelo de errores en la [guía de errores](../reference/errors_es.md).
