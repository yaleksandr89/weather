# Seguridad

## Elige un idioma

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/SECURITY.md) | [English](./SECURITY_en.md) | **Español** | [中文](./SECURITY_zh.md) | [Français](./SECURITY_fr.md) | [Deutsch](./SECURITY_de.md) |

Informa de las posibles vulnerabilidades de manera responsable. Primero determina si el problema requiere un canal privado o si es adecuado para un Issue normal.

## Qué debe comunicarse en privado

- Un comportamiento del paquete que pueda exponer una clave de API de WeatherAPI en excepciones, salida de depuración o logs.
- Un comportamiento del paquete que pueda enviar credenciales o datos de la solicitud a un host no previsto.
- Una vulnerabilidad en el tratamiento del transporte o de la respuesta de un proveedor con un impacto concreto en la seguridad, en lugar de un error de mapeo ordinario.
- Un problema explotable en una dependencia que afecte de forma sustancial a este paquete.
- La alteración de un artefacto de versión o de código fuente, u otro problema de la cadena de suministro.

## Qué puede publicarse en Issues

- El mapeo incorrecto de un campo meteorológico.
- El mapeo incorrecto o no compatible de una condición meteorológica.
- Un error de validación sin impacto en la seguridad.
- Un comportamiento relacionado con la disponibilidad del proveedor o los límites de frecuencia.
- Un problema de documentación.
- Una solicitud de funcionalidad.

## Cómo informar

- Si en el futuro GitHub muestra un formulario privado para informar de vulnerabilidades en este repositorio, es preferible utilizarlo.
- Mientras no haya un formulario privado, crea un Issue público mínimo sin código de explotación, claves de API ni detalles sensibles y solicita un canal de contacto privado.
- No publiques detalles técnicos de la explotación antes de establecer un canal privado.

## Qué incluir

Cuando sea posible, incluye:

- la versión afectada del paquete o el SHA del commit;
- la versión de PHP;
- el proveedor afectado: `Open-Meteo`, `WeatherAPI`, un proveedor personalizado o un comportamiento independiente del proveedor;
- el impacto del problema;
- una reproducción mínima;
- un fragmento depurado de la solicitud o respuesta, si es relevante.

Nunca incluyas claves de API reales ni otros secretos.

## Qué ocurrirá después

- Este es un proyecto pequeño mantenido por un solo autor; el mantenedor intentará confirmar la recepción del informe, reproducir el problema y preparar una corrección.
- No hay un SLA garantizado.
- No se promete ningún programa de recompensas por errores.
- Coordina la divulgación pública de los detalles hasta que haya una corrección disponible.
