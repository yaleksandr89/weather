# Weather

[![Source Code](https://img.shields.io/badge/source-yaleksandr89%2Fweather-blue.svg?style=flat-square)](https://github.com/yaleksandr89/weather)
[![CI](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml/badge.svg)](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml)
[![Codecov](https://codecov.io/gh/yaleksandr89/weather/graph/badge.svg)](https://codecov.io/gh/yaleksandr89/weather)
[![Latest Stable Version](https://img.shields.io/packagist/v/yaleksandr89/weather.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/weather)
[![Total Downloads](https://img.shields.io/packagist/dt/yaleksandr89/weather.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/weather)
[![PHP](https://img.shields.io/badge/PHP-8.4%2B-777BB4.svg?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![guzzlehttp/guzzle](https://img.shields.io/badge/guzzlehttp%2Fguzzle-%5E8.0.1-4E5D94.svg?style=flat-square)](https://packagist.org/packages/guzzlehttp/guzzle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](../../LICENSE)

<p align="center">
  <img
    src="../assets/weather-readme-cover.png"
    alt="Weather — current weather PHP package with unified provider normalization"
    width="100%"
  >
</p>

## Sprache wählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../README.md) | [English](./README_en.md) | [Español](./README_es.md) | [中文](./README_zh.md) | [Français](./README_fr.md) | **Ausgewählt** |

Bibliothek zum Abrufen des aktuellen Wetters anhand von Koordinaten über WeatherAPI und Open-Meteo.
Daten verschiedener Dienste werden in ein einheitliches typisiertes Format und gemeinsame Einheiten normalisiert.

## Wofür das Paket gedacht ist

WeatherAPI und Open-Meteo verwenden unterschiedliche HTTP-APIs und Antwortformate. Weather stellt eine einheitliche PHP-API zum Abrufen des aktuellen Wetters anhand von Koordinaten bereit, sodass Anwendungscode keine dienstspezifischen Besonderheiten berücksichtigen muss.

## Was das Paket macht

- ruft das aktuelle Wetter anhand von Breiten- und Längengrad ab;
- unterstützt WeatherAPI und Open-Meteo;
- liefert das Ergebnis als `CurrentWeather`;
- normalisiert Wetterdaten auf einheitliche metrische Einheiten;
- stellt typisierte Wetterzustände über `WeatherCondition` bereit;
- ermöglicht eigene Datenquellen über `CurrentWeatherProvider`.

## Anforderungen

- `PHP ^8.4`;
- `Composer`.

## Schnellstart

Installiere das Paket:

```bash
composer require yaleksandr89/weather
```

### Open-Meteo

Für Open-Meteo ist kein API key erforderlich.

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
<summary>Reale Open-Meteo-Antwort und CurrentWeather anzeigen</summary>

#### Ausschnitt einer realen Open-Meteo-Antwort

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

#### Ergebnis nach der Normalisierung

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

WeatherAPI benötigt einen API key:

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

Ersetze `YOUR_WEATHERAPI_KEY` durch deinen Schlüssel. Lege keinen echten Schlüssel im Repository ab; speichere ihn außerhalb des Quellcodes, zum Beispiel in der Anwendungskonfiguration, in Umgebungsvariablen oder in einem Secret Store.

<details>
<summary>Reale WeatherAPI-Antwort und CurrentWeather anzeigen</summary>

#### Ausschnitt einer realen WeatherAPI-Antwort

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

#### Ergebnis nach der Normalisierung

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

Beide Provider liefern `CurrentWeather`. Die vollständige Datenstruktur, Einheiten, optionale Werte und Anwendungsbeispiele sind im [`CurrentWeather`-Leitfaden](../reference/current-weather_de.md) beschrieben. Die Konfiguration der integrierten Dienste und ihre Unterschiede stehen im [Provider-Leitfaden](../guides/providers_de.md).

## Fehlerbehandlung

Eingabefehler sind von Fehlern getrennt, die beim Aufruf eines Wetterdienstes entstehen.

Regeln zur Behandlung und die Exception-Hierarchie sind im [Fehlerleitfaden](../reference/errors_de.md) beschrieben.

## Eigener Provider

Wenn WeatherAPI und Open-Meteo nicht passen, kannst du `CurrentWeatherProvider` implementieren und direkt übergeben:

```php
use Yaleksandr\Weather\Weather;

$weather = new Weather($customProvider);
```

Der Vertrag und ein Implementierungsbeispiel stehen im [Leitfaden für eigene Provider](../guides/custom-provider_de.md).

## Einschränkungen

- nur aktuelles Wetter;
- Koordinaten müssen im Voraus bekannt sein;
- keine Stadtsuche oder Geokodierung;
- kein automatischer Wechsel zwischen Providern;
- keine automatischen Retry- oder Cache-Mechanismen.

## Feedback

- reproduzierbare Fehler — [GitHub Issues](https://github.com/yaleksandr89/weather/issues).

---

<p align="center">
  Wenn dir das Paket hilft, gib ihm einen Stern auf GitHub — so finden es andere Entwickler leichter. 🤘
</p>
