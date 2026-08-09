# Fournisseur personnalisé

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./custom-provider.md) | [English](./custom-provider_en.md) | [Español](./custom-provider_es.md) | [中文](./custom-provider_zh.md) | **Sélectionné** | [Deutsch](./custom-provider_de.md) |

Si vous avez besoin d’une autre source de météo actuelle, implémentez `CurrentWeatherProvider` et passez-le à `Weather`.

## CurrentWeatherProvider

Le contrat de l’interface est le suivant :

```php
namespace Yaleksandr\Weather\Contract;

use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;

interface CurrentWeatherProvider
{
    public function current(Coordinates $coordinates): CurrentWeather;
}
```

## Exemple d’implémentation

Le fournisseur récupère les données de son service et les convertit vers le modèle public.

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
        // Interrogez ici votre service et convertissez sa réponse.
        return CurrentWeather::fromObservation(
            $coordinates,
            new \DateTimeImmutable(),
            Temperature::fromCelsius(20.0),
            WeatherCondition::Clear,
        );
    }
}
```

## Connexion

Passez directement l’implémentation au constructeur de `Weather`.

```php
use Yaleksandr\Weather\Weather;
use Yaleksandr\Weather\Value\Coordinates;

$customProvider = new ExampleCurrentWeatherProvider();
$weather = new Weather($customProvider);
$current = $weather->current(Coordinates::fromDegrees(55.7558, 37.6173));
```

## Responsabilités du fournisseur

- récupérer les données de sa source ;
- les convertir en `CurrentWeather` ;
- respecter les unités et les contraintes du modèle public ;
- gérer correctement les erreurs de l’intégration.

La structure du résultat est décrite dans le [guide `CurrentWeather`](../reference/current-weather_fr.md) et le modèle d’erreurs dans le [guide des erreurs](../reference/errors_fr.md).
