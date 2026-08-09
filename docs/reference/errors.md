# Ошибки

## Выберите язык

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| **Выбран** | [English](./errors_en.md) | [Español](./errors_es.md) | [中文](./errors_zh.md) | [Français](./errors_fr.md) | [Deutsch](./errors_de.md) |

Ошибки входных данных и конфигурации отделены от ошибок при обращении к внешнему сервису и обработке его ответа.

## Ошибки валидации и конфигурации

Следующие исключения наследуются от `InvalidArgumentException`:

| Исключение | Когда возникает |
|---|---|
| `InvalidCoordinatesException` | Некорректны широта или долгота |
| `InvalidWeatherApiConfigException` | В `WeatherApiConfig` передан пустой или состоящий из пробелов API key |
| `InvalidTemperatureException` | В `Temperature` передано некорректное значение |
| `InvalidWindException` | Некорректны параметры `Wind` |
| `InvalidCurrentWeatherException` | Некорректны значения при создании `CurrentWeather` |

Например, координаты можно обработать до выполнения запроса:

```php
use Yaleksandr\Weather\Exception\InvalidCoordinatesException;
use Yaleksandr\Weather\Value\Coordinates;

try {
    $coordinates = Coordinates::fromDegrees($latitude, $longitude);
} catch (InvalidCoordinatesException) {
    // Передайте допустимые значения широты и долготы.
}
```

## Ошибки провайдера и транспорта

`WeatherException` наследуется от `RuntimeException` и является базовым классом ошибок выполнения.

| Исключение | Возможная причина |
|---|---|
| `AuthenticationException` | Провайдер отклонил аутентификацию |
| `LocationNotFoundException` | Провайдер не нашёл локацию по запросу |
| `RateLimitException` | Провайдер ограничил запросы |
| `ProviderUnavailableException` | Сервис вернул неуспешный ответ, не отнесённый к более точной категории |
| `TransportException` | Ошибка транспорта при HTTP-запросе |
| `MalformedResponseException` | Ответ невозможно разобрать или преобразовать в модель пакета |

Обрабатывайте эти ошибки отдельно от ошибок валидации:

```php
use Yaleksandr\Weather\Exception\WeatherException;

try {
    $current = $weather->current($coordinates);
} catch (WeatherException $exception) {
    // Выберите действие, подходящее для вашего приложения.
}
```

Не полагайтесь на текст исключения как на контракт и не записывайте API key в логи или сообщения об ошибках.
