# CurrentWeather

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./current-weather.md) | [English](./current-weather_en.md) | [Español](./current-weather_es.md) | [中文](./current-weather_zh.md) | **Sélectionné** | [Deutsch](./current-weather_de.md) |

`Weather::current()` retourne `CurrentWeather`. Ce modèle typé contient les coordonnées de la requête, l’heure d’observation et les données météo normalisées.

## Champs de `CurrentWeather`

| Donnée | Méthode | Type | Unité | Peut être `null` |
|---|---|---|---|---|
| Coordonnées | `coordinates()` | `Coordinates` | degrés | Non |
| Heure d’observation | `observedAt()` | `DateTimeImmutable` | — | Non |
| Température | `temperature()` | `Temperature` | °C | Non |
| État | `condition()` | `WeatherCondition` | — | Non |
| Température ressentie | `feelsLike()` | `Temperature` | °C | Oui |
| Humidité | `humidityPercent()` | `float` | % | Oui |
| Pression | `pressureHectopascals()` | `float` | hPa | Oui |
| Vent | `wind()` | `Wind` | m/s, degrés | Oui |
| Précipitations | `precipitationMillimeters()` | `float` | mm | Oui |

Un champ facultatif peut être absent de la réponse d’un fournisseur. Vérifiez qu’il n’est pas `null` avant de l’utiliser.

## Coordonnées

`Coordinates::fromDegrees($latitude, $longitude)` crée un objet à partir de la latitude et de la longitude en degrés. La latitude doit être comprise dans `[-90, 90]`, la longitude dans `[-180, 180]` ; les deux valeurs doivent être finies.

```php
$coordinates = $current->coordinates();
echo $coordinates->latitude;
echo $coordinates->longitude;
```

## Heure d’observation

`observedAt()` retourne un `DateTimeImmutable` correspondant à l’heure d’observation fournie par le service.

```php
$observedAt = $current->observedAt();
```

## Température

`temperature()` retourne toujours `Temperature` en °C. `feelsLike()` retourne `?Temperature` et peut être `null`.

```php
$celsius = $current->temperature()->celsius();
$fahrenheit = $current->temperature()->fahrenheit();
$feelsLike = $current->feelsLike()?->celsius();
```

`fahrenheit()` effectue une conversion supplémentaire ; la valeur de base de `Temperature` est stockée en °C.

## Humidité et pression

L’humidité est mesurée en pourcentage et la pression en hPa.

```php
$humidity = $current->humidityPercent();
$pressure = $current->pressureHectopascals();
```

## Vent

`wind()` retourne `?Wind`. Si les données de vent sont disponibles, la vitesse est mesurée en m/s ; la direction et les rafales peuvent être `null`. La direction est exprimée en degrés `[0, 360)` et les rafales en m/s.

```php
if (($wind = $current->wind()) !== null) {
    $speed = $wind->speedMetersPerSecond();
    $direction = $wind->directionDegrees();
    $gust = $wind->gustMetersPerSecond();
}
```

## Précipitations

`precipitationMillimeters()` retourne la quantité de précipitations en mm ou `null`.

## État météo

`condition()` retourne l’enum `WeatherCondition`. Sa valeur textuelle (`$current->condition()->value`) correspond à l’un des cas suivants :

```text
clear
partly_cloudy
cloudy
fog
drizzle
rain
snow
sleet
thunderstorm
unknown
```

La validation des entrées et les erreurs de requêtes externes sont décrites dans le [guide des erreurs](errors_fr.md).
