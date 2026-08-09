# Fournisseurs

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./providers.md) | [English](./providers_en.md) | [Español](./providers_es.md) | [中文](./providers_zh.md) | **Sélectionné** | [Deutsch](./providers_de.md) |

WeatherAPI et Open-Meteo sont intégrés. Lors de la création de `Weather`, choisissez la configuration correspondante ; l’appel à `current()` est ensuite identique. Les deux retournent `CurrentWeather`, tandis que les formats, unités et codes propres à chaque service sont normalisés dans la bibliothèque.

## Open-Meteo

`OpenMeteoConfig` ne prend aucun paramètre. L’intégration fournie ne nécessite pas d’API key.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);
```

### Réponse réelle et normalisation

Extrait d’une réponse réelle d’Open-Meteo :

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

Après normalisation, la bibliothèque retourne :

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

Open-Meteo fournit déjà la vitesse du vent et les rafales en m/s ; ces valeurs sont donc placées dans `Wind` sans conversion d’unité. Le Unix timestamp de `time` devient un `DateTimeImmutable` en UTC, `weather_code` est converti en `WeatherCondition` et le résultat conserve les coordonnées de la requête d’origine.

## WeatherAPI

`WeatherApiConfig` nécessite une API key non vide.

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

Remplacez `YOUR_WEATHERAPI_KEY` par votre clé et n’ajoutez pas de clé réelle au dépôt ni aux logs. Le stockage relève de l’application ; utilisez par exemple sa configuration, des variables d’environnement ou un coffre de secrets.

### Réponse réelle et normalisation

Extrait d’une réponse réelle de WeatherAPI :

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

Après normalisation, la bibliothèque retourne :

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

WeatherAPI fournit le vent en km/h, tandis que le modèle public utilise les m/s : `12.6 km/h` devient `3.5 m/s` et `15.8 km/h` devient `4.3888888888889 m/s`. `condition.code` est converti en `WeatherCondition` (`1000` → `clear`), `last_updated_epoch` devient l’heure d’observation en UTC et `pressure_mb` est utilisé comme pression en hPa. Comme avec Open-Meteo, `CurrentWeather` conserve les coordonnées de la requête d’origine.

## Choisir un fournisseur

| Fournisseur | Configuration | API key requise |
|---|---|---|
| Open-Meteo | `OpenMeteoConfig` | Non |
| WeatherAPI | `WeatherApiConfig` | Oui |

Les deux fournisseurs intégrés retournent le même modèle public. La structure complète du résultat, les unités et les valeurs facultatives sont décrites dans le [guide `CurrentWeather`](../reference/current-weather_fr.md).

## Documentation officielle

- [WeatherAPI](https://www.weatherapi.com/docs/)
- [Open-Meteo](https://open-meteo.com/en/docs)

Si vous avez besoin d’une autre source de données, implémentez un [fournisseur personnalisé](custom-provider_fr.md).
