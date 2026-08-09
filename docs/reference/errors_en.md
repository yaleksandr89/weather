# Errors

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./errors.md) | **Selected** | [Español](./errors_es.md) | [中文](./errors_zh.md) | [Français](./errors_fr.md) | [Deutsch](./errors_de.md) |

Input and configuration errors are separated from errors that occur while calling an external service or processing its response.

## Validation and configuration errors

The following exceptions extend `InvalidArgumentException`:

| Exception | When it occurs |
|---|---|
| `InvalidCoordinatesException` | Latitude or longitude is invalid |
| `InvalidWeatherApiConfigException` | `WeatherApiConfig` received an empty or whitespace-only API key |
| `InvalidTemperatureException` | `Temperature` received an invalid value |
| `InvalidWindException` | `Wind` parameters are invalid |
| `InvalidCurrentWeatherException` | Values used to create `CurrentWeather` are invalid |

For example, coordinates can be validated before the request is made:

```php
use Yaleksandr\Weather\Exception\InvalidCoordinatesException;
use Yaleksandr\Weather\Value\Coordinates;

try {
    $coordinates = Coordinates::fromDegrees($latitude, $longitude);
} catch (InvalidCoordinatesException) {
    // Provide valid latitude and longitude values.
}
```

## Provider and transport errors

`WeatherException` extends `RuntimeException` and is the base class for runtime errors.

| Exception | Possible cause |
|---|---|
| `AuthenticationException` | The provider rejected authentication |
| `LocationNotFoundException` | The provider could not resolve the requested location |
| `RateLimitException` | The provider rate-limited requests |
| `ProviderUnavailableException` | The service returned an unsuccessful response that does not fit a more specific category |
| `TransportException` | An HTTP transport error occurred |
| `MalformedResponseException` | The response could not be parsed or converted to the package model |

Handle these separately from validation errors:

```php
use Yaleksandr\Weather\Exception\WeatherException;

try {
    $current = $weather->current($coordinates);
} catch (WeatherException $exception) {
    // Choose the action appropriate for your application.
}
```

Do not rely on exception text as a contract, and do not write API keys to logs or error messages.
