# Sicherheit

## Sprache wählen

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/SECURITY.md) | [English](./SECURITY_en.md) | [Español](./SECURITY_es.md) | [中文](./SECURITY_zh.md) | [Français](./SECURITY_fr.md) | **Deutsch** |

Bitte melde potenzielle Schwachstellen verantwortungsvoll. Prüfe zuerst, ob das Problem einen privaten Kanal erfordert oder für ein gewöhnliches Issue geeignet ist.

## Was privat gemeldet werden sollte

- Paketverhalten, durch das ein WeatherAPI-API-Schlüssel in Exceptions, Debug-Ausgaben oder Logs offengelegt werden kann.
- Paketverhalten, durch das Zugangsdaten oder Anfragedaten an einen unbeabsichtigten Host gesendet werden können.
- Eine Schwachstelle bei der Verarbeitung des Provider-Transports oder der Antwort mit konkreten Sicherheitsauswirkungen, nicht nur ein gewöhnlicher Mapping-Fehler.
- Ein ausnutzbares Problem in einer Abhängigkeit, das dieses Paket wesentlich betrifft.
- Die Kompromittierung eines Release- oder Quellcode-Artefakts oder ein anderes Lieferkettenproblem.

## Was in Issues veröffentlicht werden kann

- Ein fehlerhaftes Mapping eines Wetterfelds.
- Ein fehlerhaftes oder nicht unterstütztes Mapping eines Wetterzustands.
- Ein Validierungsfehler ohne Sicherheitsauswirkungen.
- Verhalten im Zusammenhang mit Provider-Verfügbarkeit oder Ratenbegrenzungen.
- Ein Dokumentationsproblem.
- Ein Funktionswunsch.

## So meldest du ein Problem

- Falls GitHub künftig ein privates Formular zur Meldung von Schwachstellen für dieses Repository anzeigt, verwende vorzugsweise dieses Formular.
- Solange kein privates Formular verfügbar ist, erstelle ein minimales öffentliches Issue ohne Exploit-Code, API-Schlüssel oder sensible Details und bitte um einen privaten Kontaktkanal.
- Veröffentliche keine technischen Exploit-Details, bevor ein privater Kanal eingerichtet wurde.

## Benötigte Angaben

Gib nach Möglichkeit Folgendes an:

- die betroffene Paketversion oder Commit-SHA;
- die PHP-Version;
- den betroffenen Provider: `Open-Meteo`, `WeatherAPI`, einen benutzerdefinierten Provider oder Provider-unabhängiges Verhalten;
- die Auswirkungen des Problems;
- eine minimale Reproduktion;
- falls relevant, einen bereinigten Anfrage- oder Antwortausschnitt.

Füge niemals echte API-Schlüssel oder andere Geheimnisse hinzu.

## Wie es weitergeht

- Dies ist ein kleines Projekt, das von einem Autor gepflegt wird; der Maintainer wird versuchen, den Eingang der Meldung zu bestätigen, das Problem zu reproduzieren und eine Fehlerbehebung vorzubereiten.
- Es gibt keine garantierte SLA.
- Es wird kein Bug-Bounty-Programm zugesagt.
- Stimme die öffentliche Offenlegung von Details ab, bis eine Fehlerbehebung verfügbar ist.
