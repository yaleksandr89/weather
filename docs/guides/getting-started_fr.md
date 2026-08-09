# Prise en main

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./getting-started.md) | [English](./getting-started_en.md) | [Español](./getting-started_es.md) | [中文](./getting-started_zh.md) | **Sélectionné** | [Deutsch](./getting-started_de.md) |

Ce guide présente l’installation, le choix d’un fournisseur intégré, la création de coordonnées et la première requête de météo actuelle.

## Prérequis

`PHP ^8.4` et `Composer` sont nécessaires.

## Installation

```bash
composer require yaleksandr89/weather
```

## Choisir un fournisseur

Le plus simple est de commencer avec Open-Meteo : l’intégration fournie ne nécessite pas d’API key. Dans les deux cas, créez `Weather` avec `Weather::create()`.

### Open-Meteo

`OpenMeteoConfig` ne prend aucun paramètre.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());
```

### WeatherAPI

`WeatherApiConfig` nécessite une API key.

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(
    new WeatherApiConfig('YOUR_WEATHERAPI_KEY'),
);
```

Remplacez `YOUR_WEATHERAPI_KEY` par votre clé et n’ajoutez pas de clé réelle au dépôt. Le stockage relève de l’application ; utilisez par exemple sa configuration, des variables d’environnement ou un coffre de secrets.

La comparaison détaillée et les liens vers la documentation des services figurent dans le [guide des fournisseurs](providers_fr.md).

## Coordonnées

Créez `Coordinates` à partir de la latitude et de la longitude en degrés. La latitude doit être comprise dans `[-90, 90]`, la longitude dans `[-180, 180]` ; les deux valeurs doivent être finies.

```php
use Yaleksandr\Weather\Value\Coordinates;

$coordinates = Coordinates::fromDegrees(55.7558, 37.6173);
```

## Obtenir la météo actuelle

Passez les coordonnées à `current()`.

```php
$current = $weather->current($coordinates);

echo $current->temperature()->celsius();
echo $current->condition()->value;
```

La structure du résultat et les valeurs facultatives sont décrites dans le [guide `CurrentWeather`](../reference/current-weather_fr.md). Les erreurs de données d’entrée et de requête sont détaillées dans le [guide des erreurs](../reference/errors_fr.md).
