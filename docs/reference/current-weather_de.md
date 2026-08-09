# CurrentWeather

## Sprache wählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./current-weather.md) | [English](./current-weather_en.md) | [Español](./current-weather_es.md) | [中文](./current-weather_zh.md) | [Français](./current-weather_fr.md) | **Ausgewählt** |

`Weather::current()` liefert `CurrentWeather`. Dieses typisierte Modell enthält die Koordinaten der Anfrage, die Beobachtungszeit und normalisierte Wetterdaten.

## Felder von `CurrentWeather`

| Daten | Methode | Typ | Einheit | Kann `null` sein |
|---|---|---|---|---|
| Koordinaten | `coordinates()` | `Coordinates` | Grad | Nein |
| Beobachtungszeit | `observedAt()` | `DateTimeImmutable` | — | Nein |
| Temperatur | `temperature()` | `Temperature` | °C | Nein |
| Zustand | `condition()` | `WeatherCondition` | — | Nein |
| Gefühlte Temperatur | `feelsLike()` | `Temperature` | °C | Ja |
| Luftfeuchtigkeit | `humidityPercent()` | `float` | % | Ja |
| Luftdruck | `pressureHectopascals()` | `float` | hPa | Ja |
| Wind | `wind()` | `Wind` | m/s, Grad | Ja |
| Niederschlag | `precipitationMillimeters()` | `float` | mm | Ja |

Ein optionales Feld kann in der Antwort eines Providers fehlen. Prüfe es vor der Verwendung auf `null`.

## Koordinaten

`Coordinates::fromDegrees($latitude, $longitude)` erstellt ein Objekt aus Breiten- und Längengrad in Grad. Der Breitengrad muss in `[-90, 90]`, der Längengrad in `[-180, 180]` liegen; beide Werte müssen endliche Zahlen sein.

```php
$coordinates = $current->coordinates();
echo $coordinates->latitude;
echo $coordinates->longitude;
```

## Beobachtungszeit

`observedAt()` liefert ein `DateTimeImmutable` mit der vom Provider angegebenen Beobachtungszeit.

```php
$observedAt = $current->observedAt();
```

## Temperatur

`temperature()` liefert immer `Temperature` in °C. `feelsLike()` liefert `?Temperature` und kann `null` sein.

```php
$celsius = $current->temperature()->celsius();
$fahrenheit = $current->temperature()->fahrenheit();
$feelsLike = $current->feelsLike()?->celsius();
```

`fahrenheit()` führt eine zusätzliche Umrechnung durch; der Basiswert von `Temperature` wird in °C gespeichert.

## Luftfeuchtigkeit und Luftdruck

Die Luftfeuchtigkeit wird in Prozent, der Luftdruck in hPa angegeben.

```php
$humidity = $current->humidityPercent();
$pressure = $current->pressureHectopascals();
```

## Wind

`wind()` liefert `?Wind`. Sind Winddaten verfügbar, wird die Geschwindigkeit in m/s angegeben; Richtung und Böe können `null` sein. Die Richtung wird in Grad `[0, 360)` angegeben, die Böe in m/s.

```php
if (($wind = $current->wind()) !== null) {
    $speed = $wind->speedMetersPerSecond();
    $direction = $wind->directionDegrees();
    $gust = $wind->gustMetersPerSecond();
}
```

## Niederschlag

`precipitationMillimeters()` liefert die Niederschlagsmenge in mm oder `null`.

## Wetterzustand

`condition()` liefert das Enum `WeatherCondition`. Sein String-Wert (`$current->condition()->value`) entspricht einem der folgenden Enum-Fälle:

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

Eingabevalidierung und Fehler externer Anfragen sind im [Fehlerleitfaden](errors_de.md) beschrieben.
