# 错误

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./errors.md) | [English](./errors_en.md) | [Español](./errors_es.md) | **已选择** | [Français](./errors_fr.md) | [Deutsch](./errors_de.md) |

输入与配置错误和调用外部服务或处理其响应时产生的错误相互分离。

## 验证和配置错误

以下异常继承自 `InvalidArgumentException`：

| 异常 | 发生条件 |
|---|---|
| `InvalidCoordinatesException` | 纬度或经度无效 |
| `InvalidWeatherApiConfigException` | 传给 `WeatherApiConfig` 的 API key 为空或只包含空白字符 |
| `InvalidTemperatureException` | 传给 `Temperature` 的值无效 |
| `InvalidWindException` | `Wind` 参数无效 |
| `InvalidCurrentWeatherException` | 创建 `CurrentWeather` 时使用的值无效 |

例如，可以在发起请求前验证坐标：

```php
use Yaleksandr\Weather\Exception\InvalidCoordinatesException;
use Yaleksandr\Weather\Value\Coordinates;

try {
    $coordinates = Coordinates::fromDegrees($latitude, $longitude);
} catch (InvalidCoordinatesException) {
    // 请提供有效的纬度和经度。
}
```

## 提供商和传输错误

`WeatherException` 继承自 `RuntimeException`，是运行时错误的基类。

| 异常 | 可能原因 |
|---|---|
| `AuthenticationException` | 提供商拒绝了身份验证 |
| `LocationNotFoundException` | 提供商无法找到请求的位置 |
| `RateLimitException` | 提供商限制了请求频率 |
| `ProviderUnavailableException` | 服务返回失败响应，但不属于更具体的错误类别 |
| `TransportException` | HTTP 传输发生错误 |
| `MalformedResponseException` | 响应无法解析或无法转换为包模型 |

这些错误应与验证错误分开处理：

```php
use Yaleksandr\Weather\Exception\WeatherException;

try {
    $current = $weather->current($coordinates);
} catch (WeatherException $exception) {
    // 选择适合你的应用的处理方式。
}
```

不要把异常文本当作契约，也不要把 API key 写入日志或错误消息。
