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

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../README.md) | [English](./README_en.md) | [Español](./README_es.md) | [中文](./README_zh.md) | **Sélectionné** | [Deutsch](./README_de.md) |

Bibliothèque permettant d’obtenir la météo actuelle à partir de coordonnées via WeatherAPI et Open-Meteo.
Les données de différents services sont normalisées dans un format typé unique et des unités communes.

## À quoi sert le package

WeatherAPI et Open-Meteo utilisent des API HTTP et des formats de réponse différents. Weather fournit une API PHP unique pour obtenir la météo actuelle à partir de coordonnées, afin que le code applicatif n’ait pas à gérer les particularités de chaque service.

## Ce que fait le package

- récupère la météo actuelle à partir de la latitude et de la longitude ;
- prend en charge WeatherAPI et Open-Meteo ;
- retourne le résultat sous forme de `CurrentWeather` ;
- normalise les données météo dans des unités métriques communes ;
- fournit des états météo typés via `WeatherCondition` ;
- permet de connecter une source de données personnalisée via `CurrentWeatherProvider`.

## Prérequis

- `PHP ^8.4`;
- `Composer`.

## Démarrage rapide

Installez le package :

```bash
composer require yaleksandr89/weather
```

### Open-Meteo

Open-Meteo ne nécessite pas d’API key.

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
<summary>Afficher la réponse réelle d’Open-Meteo et CurrentWeather</summary>

#### Extrait d’une réponse réelle d’Open-Meteo

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

#### Résultat après normalisation

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

WeatherAPI nécessite une API key :

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

Remplacez `YOUR_WEATHERAPI_KEY` par votre clé. N’ajoutez pas de clé réelle au dépôt ; conservez-la hors du code source, par exemple dans la configuration de l’application, des variables d’environnement ou un coffre de secrets.

<details>
<summary>Afficher la réponse réelle de WeatherAPI et CurrentWeather</summary>

#### Extrait d’une réponse réelle de WeatherAPI

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

#### Résultat après normalisation

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

Les deux fournisseurs retournent `CurrentWeather`. La structure complète des données, les unités, les valeurs facultatives et les exemples d’utilisation sont décrits dans le [guide `CurrentWeather`](../reference/current-weather_fr.md). La configuration des services intégrés et leurs différences sont présentées dans le [guide des fournisseurs](../guides/providers_fr.md).

## Gestion des erreurs

Les erreurs de saisie sont séparées des erreurs survenant lors de l’appel à un service météo.

Les règles de gestion et la hiérarchie des exceptions sont décrites dans le [guide des erreurs](../reference/errors_fr.md).

## Fournisseur personnalisé

Si WeatherAPI et Open-Meteo ne conviennent pas, vous pouvez implémenter `CurrentWeatherProvider` et le passer directement :

```php
use Yaleksandr\Weather\Weather;

$weather = new Weather($customProvider);
```

Le contrat et un exemple d’implémentation sont disponibles dans le [guide du fournisseur personnalisé](../guides/custom-provider_fr.md).

## Limites

- seule la météo actuelle est prise en charge ;
- les coordonnées doivent être connues à l’avance ;
- aucune recherche de ville ni géocodage ;
- aucun basculement automatique entre fournisseurs ;
- aucun retry ni cache automatique.

## Retour

- bugs reproductibles — [GitHub Issues](https://github.com/yaleksandr89/weather/issues).

---

<p align="center">
  Si le package vous est utile, ajoutez une étoile sur GitHub : cela aidera d’autres développeurs à le découvrir. 🤘
</p>
