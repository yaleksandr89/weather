# Eigener Provider

## Sprache wählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./custom-provider.md) | [English](./custom-provider_en.md) | [Español](./custom-provider_es.md) | [中文](./custom-provider_zh.md) | [Français](./custom-provider_fr.md) | **Ausgewählt** |

Wenn du eine andere Quelle für aktuelles Wetter benötigst, implementiere `CurrentWeatherProvider` und übergib ihn an `Weather`.

## CurrentWeatherProvider

Der Interface-Vertrag sieht so aus:

```php
namespace Yaleksandr\Weather\Contract;

use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;

interface CurrentWeatherProvider
{
    public function current(Coordinates $coordinates): CurrentWeather;
}
```

## Implementierungsbeispiel

Der Provider ruft Daten seines Dienstes ab und wandelt sie in das öffentliche Modell um.

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
        // Rufe hier deinen Dienst auf und wandle seine Antwort um.
        return CurrentWeather::fromObservation(
            $coordinates,
            new \DateTimeImmutable(),
            Temperature::fromCelsius(20.0),
            WeatherCondition::Clear,
        );
    }
}
```

## Einbinden

Übergib die Implementierung direkt an den Konstruktor von `Weather`.

```php
use Yaleksandr\Weather\Weather;
use Yaleksandr\Weather\Value\Coordinates;

$customProvider = new ExampleCurrentWeatherProvider();
$weather = new Weather($customProvider);
$current = $weather->current(Coordinates::fromDegrees(55.7558, 37.6173));
```

## Verantwortung des Providers

- Daten von seiner Quelle abrufen;
- sie in `CurrentWeather` umwandeln;
- Einheiten und Einschränkungen des öffentlichen Modells einhalten;
- Fehler der Integration korrekt behandeln.

Die Ergebnisstruktur ist im [`CurrentWeather`-Leitfaden](../reference/current-weather_de.md) beschrieben, das Fehlermodell im [Fehlerleitfaden](../reference/errors_de.md).
