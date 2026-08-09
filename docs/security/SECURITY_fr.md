# Sécurité

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/SECURITY.md) | [English](./SECURITY_en.md) | [Español](./SECURITY_es.md) | [中文](./SECURITY_zh.md) | **Français** | [Deutsch](./SECURITY_de.md) |

Veuillez signaler les vulnérabilités potentielles de manière responsable. Déterminez d'abord si le problème nécessite un canal privé ou convient à une Issue ordinaire.

## Ce qui doit être signalé en privé

- Un comportement du paquet susceptible d'exposer une clé API WeatherAPI dans des exceptions, une sortie de débogage ou des logs.
- Un comportement du paquet susceptible d'envoyer des identifiants ou des données de requête à un hôte non prévu.
- Une vulnérabilité dans le traitement du transport ou de la réponse d'un fournisseur ayant un impact concret sur la sécurité, plutôt qu'un bug de mapping ordinaire.
- Un problème exploitable dans une dépendance qui affecte substantiellement ce paquet.
- La compromission d'un artefact de version ou de code source, ou un autre problème de chaîne d'approvisionnement.

## Ce qui peut être publié dans les Issues

- Un mapping incorrect d'un champ météorologique.
- Un mapping incorrect ou non pris en charge d'une condition météorologique.
- Un bug de validation sans impact sur la sécurité.
- Un comportement lié à la disponibilité du fournisseur ou aux limites de débit.
- Un problème de documentation.
- Une demande de fonctionnalité.

## Comment signaler

- Si GitHub affiche ultérieurement un formulaire privé de signalement de vulnérabilité pour ce dépôt, utilisez-le de préférence.
- Tant qu'aucun formulaire privé n'est disponible, créez une Issue publique minimale sans code d'exploitation, clé API ni détail sensible, et demandez un canal de contact privé.
- Ne publiez pas les détails techniques de l'exploitation avant l'établissement d'un canal privé.

## Éléments à inclure

Dans la mesure du possible, indiquez :

- la version affectée du paquet ou le SHA du commit ;
- la version de PHP ;
- le fournisseur affecté : `Open-Meteo`, `WeatherAPI`, un fournisseur personnalisé ou un comportement indépendant du fournisseur ;
- l'impact du problème ;
- une reproduction minimale ;
- un fragment nettoyé de la requête ou de la réponse, s'il est pertinent.

N'incluez jamais de vraies clés API ni d'autres secrets.

## Suite du traitement

- Il s'agit d'un petit projet maintenu par un seul auteur ; le mainteneur essaiera d'accuser réception du signalement, de reproduire le problème et de préparer un correctif.
- Aucun SLA n'est garanti.
- Aucun programme de récompense des bugs n'est promis.
- Coordonnez la divulgation publique des détails jusqu'à ce qu'un correctif soit disponible.
