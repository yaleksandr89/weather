# Provider

## Sprache wählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./providers.md) | [English](./providers_en.md) | [Español](./providers_es.md) | [中文](./providers_zh.md) | [Français](./providers_fr.md) | **Ausgewählt** |

WeatherAPI und Open-Meteo sind integriert. Wähle beim Erstellen von `Weather` die passende Konfiguration; danach ist der Aufruf von `current()` identisch. Beide liefern `CurrentWeather`, während dienstspezifische Formate, Einheiten und Codes innerhalb der Bibliothek normalisiert werden.

## Open-Meteo

`OpenMeteoConfig` benötigt keine Parameter. Die integrierte Anbindung benötigt keinen API key.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);
```

### Reale Antwort und Normalisierung

Ausschnitt einer realen Open-Meteo-Antwort:

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

Nach der Normalisierung liefert die Bibliothek:

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

Open-Meteo liefert Windgeschwindigkeit und Böen bereits in m/s, daher werden diese Werte ohne Einheitenumrechnung in `Wind` übernommen. Der Unix timestamp aus `time` wird zu einem `DateTimeImmutable` in UTC, `weather_code` wird in `WeatherCondition` abgebildet, und das Ergebnis behält die Koordinaten der ursprünglichen Anfrage.

## WeatherAPI

`WeatherApiConfig` benötigt einen nicht leeren API key.

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

Ersetze `YOUR_WEATHERAPI_KEY` durch deinen Schlüssel und lege keinen echten Schlüssel im Repository oder in Logs ab. Die Speicherung liegt in der Verantwortung der Anwendung; verwende zum Beispiel Anwendungskonfiguration, Umgebungsvariablen oder einen Secret Store.

### Reale Antwort und Normalisierung

Ausschnitt einer realen WeatherAPI-Antwort:

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

Nach der Normalisierung liefert die Bibliothek:

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

WeatherAPI liefert Windwerte in km/h, während das öffentliche Modell m/s verwendet: `12.6 km/h` wird zu `3.5 m/s`, `15.8 km/h` zu `4.3888888888889 m/s`. `condition.code` wird in `WeatherCondition` abgebildet (`1000` → `clear`), `last_updated_epoch` wird zur Beobachtungszeit in UTC und `pressure_mb` wird als Druck in hPa verwendet. Wie bei Open-Meteo behält `CurrentWeather` die Koordinaten der ursprünglichen Anfrage.

## Provider auswählen

| Provider | Konfiguration | API key erforderlich |
|---|---|---|
| Open-Meteo | `OpenMeteoConfig` | Nein |
| WeatherAPI | `WeatherApiConfig` | Ja |

Beide integrierten Provider liefern dasselbe öffentliche Modell. Die vollständige Ergebnisstruktur, Einheiten und optionalen Werte sind im [`CurrentWeather`-Leitfaden](../reference/current-weather_de.md) beschrieben.

## Offizielle Dokumentation

- [WeatherAPI](https://www.weatherapi.com/docs/)
- [Open-Meteo](https://open-meteo.com/en/docs)

Wenn du eine andere Datenquelle benötigst, implementiere einen [eigenen Provider](custom-provider_de.md).
