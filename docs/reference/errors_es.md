# Errores

## Elige un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./errors.md) | [English](./errors_en.md) | **Seleccionado** | [中文](./errors_zh.md) | [Français](./errors_fr.md) | [Deutsch](./errors_de.md) |

Los errores de entrada y configuración se separan de los errores que se producen al consultar un servicio externo o procesar su respuesta.

## Errores de validación y configuración

Las siguientes excepciones heredan de `InvalidArgumentException`:

| Excepción | Cuándo ocurre |
|---|---|
| `InvalidCoordinatesException` | La latitud o la longitud no son válidas |
| `InvalidWeatherApiConfigException` | `WeatherApiConfig` recibió una API key vacía o formada solo por espacios |
| `InvalidTemperatureException` | `Temperature` recibió un valor no válido |
| `InvalidWindException` | Los parámetros de `Wind` no son válidos |
| `InvalidCurrentWeatherException` | Los valores usados para crear `CurrentWeather` no son válidos |

Por ejemplo, las coordenadas pueden validarse antes de realizar la solicitud:

```php
use Yaleksandr\Weather\Exception\InvalidCoordinatesException;
use Yaleksandr\Weather\Value\Coordinates;

try {
    $coordinates = Coordinates::fromDegrees($latitude, $longitude);
} catch (InvalidCoordinatesException) {
    // Proporciona valores válidos de latitud y longitud.
}
```

## Errores del proveedor y del transporte

`WeatherException` hereda de `RuntimeException` y es la clase base de los errores de ejecución.

| Excepción | Posible causa |
|---|---|
| `AuthenticationException` | El proveedor rechazó la autenticación |
| `LocationNotFoundException` | El proveedor no encontró la ubicación solicitada |
| `RateLimitException` | El proveedor limitó la frecuencia de solicitudes |
| `ProviderUnavailableException` | El servicio devolvió una respuesta no satisfactoria que no encaja en una categoría más específica |
| `TransportException` | Se produjo un error del transporte HTTP |
| `MalformedResponseException` | La respuesta no pudo analizarse o convertirse al modelo del paquete |

Gestiona estos errores por separado de los errores de validación:

```php
use Yaleksandr\Weather\Exception\WeatherException;

try {
    $current = $weather->current($coordinates);
} catch (WeatherException $exception) {
    // Elige la acción adecuada para tu aplicación.
}
```

No dependas del texto de la excepción como contrato y no escribas API keys en logs ni mensajes de error.
