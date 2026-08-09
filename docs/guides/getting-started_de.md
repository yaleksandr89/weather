# Erste Schritte

## Sprache wählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./getting-started.md) | [English](./getting-started_en.md) | [Español](./getting-started_es.md) | [中文](./getting-started_zh.md) | [Français](./getting-started_fr.md) | **Ausgewählt** |

Dieser Leitfaden zeigt Installation, Auswahl eines integrierten Providers, Erstellen von Koordinaten und die erste Abfrage des aktuellen Wetters.

## Anforderungen

Benötigt werden `PHP ^8.4` und `Composer`.

## Installation

```bash
composer require yaleksandr89/weather
```

## Provider auswählen

Am einfachsten ist der Einstieg mit Open-Meteo: Die integrierte Anbindung benötigt keinen API key. Erstelle für beide Varianten `Weather` mit `Weather::create()`.

### Open-Meteo

`OpenMeteoConfig` benötigt keine Parameter.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());
```

### WeatherAPI

`WeatherApiConfig` benötigt einen API key.

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(
    new WeatherApiConfig('YOUR_WEATHERAPI_KEY'),
);
```

Ersetze `YOUR_WEATHERAPI_KEY` durch deinen Schlüssel und lege keinen echten Schlüssel im Repository ab. Die Speicherung liegt in der Verantwortung der Anwendung; verwende zum Beispiel Anwendungskonfiguration, Umgebungsvariablen oder einen Secret Store.

Einen detaillierten Vergleich und Links zur Servicedokumentation findest du im [Provider-Leitfaden](providers_de.md).

## Koordinaten

Erstelle `Coordinates` aus Breiten- und Längengrad in Grad. Der Breitengrad muss in `[-90, 90]`, der Längengrad in `[-180, 180]` liegen; beide Werte müssen endliche Zahlen sein.

```php
use Yaleksandr\Weather\Value\Coordinates;

$coordinates = Coordinates::fromDegrees(55.7558, 37.6173);
```

## Aktuelles Wetter abrufen

Übergib die Koordinaten an `current()`.

```php
$current = $weather->current($coordinates);

echo $current->temperature()->celsius();
echo $current->condition()->value;
```

Die Ergebnisfelder und optionalen Werte sind im [`CurrentWeather`-Leitfaden](../reference/current-weather_de.md) beschrieben. Fehler bei Eingabedaten und Serviceanfragen stehen im [Fehlerleitfaden](../reference/errors_de.md).
