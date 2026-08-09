# Fehler

## Sprache wählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./errors.md) | [English](./errors_en.md) | [Español](./errors_es.md) | [中文](./errors_zh.md) | [Français](./errors_fr.md) | **Ausgewählt** |

Eingabe- und Konfigurationsfehler sind von Fehlern getrennt, die beim Aufruf eines externen Dienstes oder beim Verarbeiten seiner Antwort entstehen.

## Validierungs- und Konfigurationsfehler

Die folgenden Exceptions erben von `InvalidArgumentException`:

| Exception | Wann sie auftritt |
|---|---|
| `InvalidCoordinatesException` | Breiten- oder Längengrad ist ungültig |
| `InvalidWeatherApiConfigException` | `WeatherApiConfig` hat einen leeren oder nur aus Leerzeichen bestehenden API key erhalten |
| `InvalidTemperatureException` | `Temperature` hat einen ungültigen Wert erhalten |
| `InvalidWindException` | Parameter von `Wind` sind ungültig |
| `InvalidCurrentWeatherException` | Werte zum Erstellen von `CurrentWeather` sind ungültig |

Koordinaten können zum Beispiel vor der Anfrage validiert werden:

```php
use Yaleksandr\Weather\Exception\InvalidCoordinatesException;
use Yaleksandr\Weather\Value\Coordinates;

try {
    $coordinates = Coordinates::fromDegrees($latitude, $longitude);
} catch (InvalidCoordinatesException) {
    // Übergib gültige Werte für Breiten- und Längengrad.
}
```

## Provider- und Transportfehler

`WeatherException` erbt von `RuntimeException` und ist die Basisklasse für Laufzeitfehler.

| Exception | Mögliche Ursache |
|---|---|
| `AuthenticationException` | Der Provider hat die Authentifizierung abgelehnt |
| `LocationNotFoundException` | Der Provider konnte den angefragten Ort nicht finden |
| `RateLimitException` | Der Provider hat die Anfragen begrenzt |
| `ProviderUnavailableException` | Der Dienst hat eine nicht erfolgreiche Antwort geliefert, die keiner genaueren Kategorie zugeordnet werden kann |
| `TransportException` | Beim HTTP-Transport ist ein Fehler aufgetreten |
| `MalformedResponseException` | Die Antwort konnte nicht geparst oder in das Paketmodell umgewandelt werden |

Behandle diese Fehler getrennt von Validierungsfehlern:

```php
use Yaleksandr\Weather\Exception\WeatherException;

try {
    $current = $weather->current($coordinates);
} catch (WeatherException $exception) {
    // Wähle die für deine Anwendung passende Aktion.
}
```

Verlasse dich nicht auf den Exception-Text als Vertrag und schreibe keine API keys in Logs oder Fehlermeldungen.
