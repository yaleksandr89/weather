# Security

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/SECURITY.md) | **English** | [Español](./SECURITY_es.md) | [中文](./SECURITY_zh.md) | [Français](./SECURITY_fr.md) | [Deutsch](./SECURITY_de.md) |

Please report potential vulnerabilities responsibly. First determine whether the problem requires a private channel or is suitable for a regular Issue.

## What should be reported privately

- Package behavior that can expose a WeatherAPI API key in exceptions, debugging output, or logs.
- Package behavior that can send credentials or request data to an unintended host.
- A vulnerability in provider transport or response handling with a concrete security impact, rather than an ordinary mapping bug.
- An exploitable dependency issue that materially affects this package.
- Compromise of a release or source artifact, or another supply-chain issue.

## What can be published in Issues

- Incorrect weather field mapping.
- Incorrect or unsupported weather condition mapping.
- A validation bug without security impact.
- Behavior related to provider availability or rate limits.
- A documentation problem.
- A feature request.

## How to report

- If GitHub later displays a private vulnerability report form for this repository, prefer using it.
- While no private form is available, create a minimal public Issue without exploit code, API keys, or sensitive details and request a private contact channel.
- Do not publish technical exploit details before a private channel is established.

## What to include

When possible, include:

- the affected package version or commit SHA;
- the PHP version;
- the affected provider: `Open-Meteo`, `WeatherAPI`, a custom provider, or provider-independent behavior;
- the impact of the issue;
- a minimal reproduction;
- a sanitized request or response fragment if relevant.

Never include real API keys or other secrets.

## What happens next

- This is a small project maintained by one author; the maintainer will try to acknowledge the report, reproduce the issue, and prepare a fix.
- There is no guaranteed SLA.
- No bug bounty program is promised.
- Coordinate public disclosure of details until a fix is available.
