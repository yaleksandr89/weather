# Contribuer

## Choisir une langue

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/CONTRIBUTING.md) | [English](./CONTRIBUTING_en.md) | [Español](./CONTRIBUTING_es.md) | [中文](./CONTRIBUTING_zh.md) | **Français** | [Deutsch](./CONTRIBUTING_de.md) |

Merci de vouloir améliorer Weather. Ce guide vous aidera à préparer une modification plus facile à examiner et à maintenir.

## Avant de commencer

- Signalez un bug reproductible au moyen d'une Issue GitHub.
- Créez une feature request pour une nouvelle fonctionnalité ou une amélioration.
- Pour un problème de sécurité, suivez la [politique de sécurité](../../.github/SECURITY.md) et ne publiez pas de détails sensibles.
- Avant de les implémenter, discutez avec le mainteneur dans une Issue des modifications importantes ou incompatibles avec les versions précédentes de l'API publique ou du contrat des fournisseurs.

## Contrat du paquet

- Le paquet récupère la météo actuelle à partir de `Coordinates` et renvoie `CurrentWeather`.
- Les fournisseurs intégrés Open-Meteo et WeatherAPI convertissent des formats de réponse différents vers le même `CurrentWeather`.
- Le comportement public commun doit rester indépendant du fournisseur sélectionné.
- Si une modification affecte la normalisation ou les invariants communs, vérifiez le comportement concerné pour chaque fournisseur affecté.
- Les fournisseurs personnalisés implémentent `CurrentWeatherProvider`.
- Les modifications de l'API publique doivent être intentionnelles et tenir compte de SemVer et de la rétrocompatibilité.
- N'ajoutez pas d'abstractions ou de fonctionnalités sans rapport avec le problème traité.

## Branches

Utilisez un nom court qui reflète l'objectif de la modification, par exemple :

```text
feature/add-provider
fix/weatherapi-condition-mapping
docs/update-provider-guide
```

## Commits

Le format Conventional Commits est recommandé. Exemples :

```text
feat: add provider integration
fix: normalize weather condition
docs: clarify provider configuration
test: cover malformed provider response
chore: update CI configuration
```

## Vérifications locales

Installez les dépendances et exécutez l'ensemble des vérifications :

```shell
composer install
composer check
```

Les vérifications ciblées suivantes sont disponibles :

```shell
composer test
composer analyse
composer cs:check
```

Vous pouvez exécuter `composer coverage` séparément lorsqu'un rapport de couverture est nécessaire ; il n'est pas obligatoire pour chaque modification.

## Pull Request

Dans la description de la Pull Request, indiquez :

- le problème et la modification apportée ;
- les vérifications effectuées ;
- l'impact sur l'API publique ou le comportement des fournisseurs ;
- les tests ajoutés ou mis à jour ;
- les modifications de la documentation ;
- si les traductions de CONTRIBUTING et SECURITY ont été synchronisées lorsque ces politiques ont changé.

Avant l'envoi, vérifiez que :

- aucune vraie clé WeatherAPI, aucun token, autre secret ou configuration privée n'a été ajouté ;
- aucune réponse de systèmes de production contenant des données privées n'apparaît dans le code, les logs, les Issues ou les données de test ;
- les fixtures de test sont synthétiques et nettoyées des données sensibles ;
- `vendor/`, les caches générés et les résultats de couverture n'ont pas été ajoutés au dépôt.
