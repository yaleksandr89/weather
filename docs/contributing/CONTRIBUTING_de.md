# Mitwirken

## Sprache wählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/CONTRIBUTING.md) | [English](./CONTRIBUTING_en.md) | [Español](./CONTRIBUTING_es.md) | [中文](./CONTRIBUTING_zh.md) | [Français](./CONTRIBUTING_fr.md) | **Deutsch** |

Vielen Dank, dass du Weather verbessern möchtest. Dieser Leitfaden hilft dir, eine Änderung vorzubereiten, die leichter zu prüfen und zu pflegen ist.

## Vor dem Start

- Melde einen reproduzierbaren Fehler über ein GitHub Issue.
- Erstelle für eine neue Funktion oder Verbesserung einen Feature Request.
- Befolge bei einem Sicherheitsproblem die [Sicherheitsrichtlinie](../../.github/SECURITY.md) und veröffentliche keine sensiblen Details.
- Besprich große oder nicht abwärtskompatible Änderungen an der öffentlichen API oder am Provider-Vertrag vor der Implementierung mit dem Maintainer in einem Issue.

## Paketvertrag

- Das Paket ruft das aktuelle Wetter anhand von `Coordinates` ab und gibt `CurrentWeather` zurück.
- Die integrierten Provider Open-Meteo und WeatherAPI wandeln unterschiedliche Antwortformate in dasselbe `CurrentWeather` um.
- Gemeinsames öffentliches Verhalten muss unabhängig vom ausgewählten Provider bleiben.
- Wenn eine Änderung die gemeinsame Normalisierung oder Invarianten betrifft, prüfe das entsprechende Verhalten für jeden betroffenen Provider.
- Benutzerdefinierte Provider implementieren `CurrentWeatherProvider`.
- Änderungen an der öffentlichen API müssen beabsichtigt sein und SemVer sowie Abwärtskompatibilität berücksichtigen.
- Füge keine Abstraktionen oder Funktionen hinzu, die nicht mit dem gelösten Problem zusammenhängen.

## Branches

Verwende einen kurzen Namen, der den Zweck der Änderung widerspiegelt, zum Beispiel:

```text
feature/add-provider
fix/weatherapi-condition-mapping
docs/update-provider-guide
```

## Commits

Conventional Commits werden empfohlen. Beispiele:

```text
feat: add provider integration
fix: normalize weather condition
docs: clarify provider configuration
test: cover malformed provider response
chore: update CI configuration
```

## Lokale Prüfungen

Installiere die Abhängigkeiten und führe alle Prüfungen aus:

```shell
composer install
composer check
```

Folgende gezielte Prüfungen sind verfügbar:

```shell
composer test
composer analyse
composer cs:check
```

Führe `composer coverage` separat aus, wenn ein Coverage-Bericht benötigt wird; der Befehl ist nicht für jede Änderung erforderlich.

## Pull Request

Nenne in der Pull-Request-Beschreibung:

- das Problem und die vorgenommene Änderung;
- die durchgeführten Prüfungen;
- Auswirkungen auf die öffentliche API oder das Verhalten der Provider;
- hinzugefügte oder aktualisierte Tests;
- Änderungen an der Dokumentation;
- ob die Übersetzungen von CONTRIBUTING und SECURITY synchronisiert wurden, falls diese Richtlinien geändert wurden.

Stelle vor dem Absenden sicher:

- Es wurden kein echter WeatherAPI-Schlüssel, keine Tokens, andere Geheimnisse oder private Konfiguration hinzugefügt;
- Antworten aus Produktionssystemen mit privaten Daten erscheinen nicht in Code, Logs, Issues oder Testdaten;
- Test-Fixtures sind synthetisch und von sensiblen Daten bereinigt;
- `vendor/`, generierte Caches und Coverage-Ausgaben wurden nicht zum Repository hinzugefügt.
