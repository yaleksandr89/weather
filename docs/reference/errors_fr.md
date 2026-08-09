# Erreurs

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./errors.md) | [English](./errors_en.md) | [Español](./errors_es.md) | [中文](./errors_zh.md) | **Sélectionné** | [Deutsch](./errors_de.md) |

Les erreurs de saisie et de configuration sont séparées des erreurs survenant lors de l’appel à un service externe ou du traitement de sa réponse.

## Erreurs de validation et de configuration

Les exceptions suivantes héritent de `InvalidArgumentException` :

| Exception | Quand elle se produit |
|---|---|
| `InvalidCoordinatesException` | La latitude ou la longitude est invalide |
| `InvalidWeatherApiConfigException` | `WeatherApiConfig` a reçu une API key vide ou composée uniquement d’espaces |
| `InvalidTemperatureException` | `Temperature` a reçu une valeur invalide |
| `InvalidWindException` | Les paramètres de `Wind` sont invalides |
| `InvalidCurrentWeatherException` | Les valeurs utilisées pour créer `CurrentWeather` sont invalides |

Par exemple, les coordonnées peuvent être validées avant d’effectuer la requête :

```php
use Yaleksandr\Weather\Exception\InvalidCoordinatesException;
use Yaleksandr\Weather\Value\Coordinates;

try {
    $coordinates = Coordinates::fromDegrees($latitude, $longitude);
} catch (InvalidCoordinatesException) {
    // Fournissez des valeurs valides de latitude et de longitude.
}
```

## Erreurs du fournisseur et du transport

`WeatherException` hérite de `RuntimeException` et constitue la classe de base des erreurs d’exécution.

| Exception | Cause possible |
|---|---|
| `AuthenticationException` | Le fournisseur a refusé l’authentification |
| `LocationNotFoundException` | Le fournisseur n’a pas trouvé l’emplacement demandé |
| `RateLimitException` | Le fournisseur a limité la fréquence des requêtes |
| `ProviderUnavailableException` | Le service a retourné une réponse en échec ne relevant pas d’une catégorie plus précise |
| `TransportException` | Une erreur de transport HTTP s’est produite |
| `MalformedResponseException` | La réponse n’a pas pu être analysée ou convertie vers le modèle du package |

Traitez ces erreurs séparément des erreurs de validation :

```php
use Yaleksandr\Weather\Exception\WeatherException;

try {
    $current = $weather->current($coordinates);
} catch (WeatherException $exception) {
    // Choisissez l’action adaptée à votre application.
}
```

Ne vous appuyez pas sur le texte de l’exception comme sur un contrat et n’écrivez pas d’API key dans les logs ou messages d’erreur.
