# Contributing

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/CONTRIBUTING.md) | **English** | [Español](./CONTRIBUTING_es.md) | [中文](./CONTRIBUTING_zh.md) | [Français](./CONTRIBUTING_fr.md) | [Deutsch](./CONTRIBUTING_de.md) |

Thank you for wanting to improve Weather. This guide will help you prepare a change that is easier to review and maintain.

## Before you start

- Report a reproducible bug through a GitHub Issue.
- Create a feature request for a new feature or improvement.
- For a security issue, follow the [security policy](../../.github/SECURITY.md) and do not publish sensitive details.
- Discuss large or backward-incompatible public API or provider contract changes with the maintainer in an Issue before implementation.

## Package contract

- The package retrieves current weather by `Coordinates` and returns `CurrentWeather`.
- The built-in Open-Meteo and WeatherAPI providers convert different response formats into the same `CurrentWeather`.
- Shared public behavior must remain independent of the selected provider.
- If a change affects shared normalization or invariants, verify the relevant behavior for every affected provider.
- Custom providers implement `CurrentWeatherProvider`.
- Public API changes must be intentional and account for SemVer and backward compatibility.
- Do not add abstractions or features unrelated to the problem being solved.

## Branches

Use a short name that reflects the purpose of the change, for example:

```text
feature/add-provider
fix/weatherapi-condition-mapping
docs/update-provider-guide
```

## Commits

Conventional Commits are recommended. Examples:

```text
feat: add provider integration
fix: normalize weather condition
docs: clarify provider configuration
test: cover malformed provider response
chore: update CI configuration
```

## Local checks

Install dependencies and run the aggregate checks:

```shell
composer install
composer check
```

The following focused checks are available:

```shell
composer test
composer analyse
composer cs:check
```

Run `composer coverage` separately when a coverage report is needed; it is not required for every change.

## Pull Request

In the Pull Request description, include:

- the problem and the change made;
- checks performed;
- impact on the public API or provider behavior;
- tests added or updated;
- documentation changes;
- whether the CONTRIBUTING and SECURITY translations were synchronized if those policies changed.

Before submitting, make sure:

- no real WeatherAPI key, tokens, other secrets, or private configuration were added;
- no production responses containing private data appear in code, logs, Issues, or test data;
- test fixtures are synthetic and sanitized;
- `vendor/`, generated caches, and coverage output were not added to the repository.
