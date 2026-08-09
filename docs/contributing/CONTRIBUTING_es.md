# Contribuir

## Elige un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/CONTRIBUTING.md) | [English](./CONTRIBUTING_en.md) | **Español** | [中文](./CONTRIBUTING_zh.md) | [Français](./CONTRIBUTING_fr.md) | [Deutsch](./CONTRIBUTING_de.md) |

Gracias por querer mejorar Weather. Esta guía te ayudará a preparar un cambio que sea más fácil de revisar y mantener.

## Antes de empezar

- Informa de un error reproducible mediante un Issue de GitHub.
- Crea una solicitud de funcionalidad para una nueva función o mejora.
- Para un problema de seguridad, sigue la [política de seguridad](../../.github/SECURITY.md) y no publiques detalles sensibles.
- Antes de implementar cambios grandes o incompatibles con versiones anteriores en la API pública o en el contrato de proveedor, coméntalos con el mantenedor en un Issue.

## Contrato del paquete

- El paquete obtiene el tiempo actual mediante `Coordinates` y devuelve `CurrentWeather`.
- Los proveedores integrados Open-Meteo y WeatherAPI convierten formatos de respuesta diferentes en el mismo `CurrentWeather`.
- El comportamiento público compartido debe seguir siendo independiente del proveedor seleccionado.
- Si un cambio afecta a la normalización o a los invariantes compartidos, verifica el comportamiento correspondiente para cada proveedor afectado.
- Los proveedores personalizados implementan `CurrentWeatherProvider`.
- Los cambios en la API pública deben ser intencionados y tener en cuenta SemVer y la compatibilidad con versiones anteriores.
- No añadas abstracciones ni funciones que no estén relacionadas con el problema que se resuelve.

## Ramas

Usa un nombre corto que refleje el propósito del cambio, por ejemplo:

```text
feature/add-provider
fix/weatherapi-condition-mapping
docs/update-provider-guide
```

## Commits

Se recomienda el formato Conventional Commits. Ejemplos:

```text
feat: add provider integration
fix: normalize weather condition
docs: clarify provider configuration
test: cover malformed provider response
chore: update CI configuration
```

## Comprobaciones locales

Instala las dependencias y ejecuta el conjunto completo de comprobaciones:

```shell
composer install
composer check
```

Están disponibles estas comprobaciones específicas:

```shell
composer test
composer analyse
composer cs:check
```

Puedes ejecutar `composer coverage` por separado cuando necesites un informe de cobertura; no es obligatorio para cada cambio.

## Pull Request

Incluye en la descripción del Pull Request:

- el problema y el cambio realizado;
- las comprobaciones ejecutadas;
- el impacto en la API pública o en el comportamiento de los proveedores;
- las pruebas añadidas o actualizadas;
- los cambios en la documentación;
- si se sincronizaron las traducciones de CONTRIBUTING y SECURITY cuando cambiaron esas políticas.

Antes de enviarlo, asegúrate de que:

- no se han añadido claves reales de WeatherAPI, tokens, otros secretos ni configuración privada;
- no aparecen respuestas de sistemas de producción con datos privados en el código, los logs, los Issues ni los datos de prueba;
- los fixtures de prueba son sintéticos y están depurados de datos sensibles;
- no se han añadido al repositorio `vendor/`, cachés generadas ni resultados de cobertura.
