# 自定义提供商

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./custom-provider.md) | [English](./custom-provider_en.md) | [Español](./custom-provider_es.md) | **已选择** | [Français](./custom-provider_fr.md) | [Deutsch](./custom-provider_de.md) |

如果需要其他当前天气数据源，请实现 `CurrentWeatherProvider` 并传给 `Weather`。

## CurrentWeatherProvider

接口约定如下：

```php
namespace Yaleksandr\Weather\Contract;

use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;

interface CurrentWeatherProvider
{
    public function current(Coordinates $coordinates): CurrentWeather;
}
```

## 实现示例

提供商从自己的服务获取数据，并将其转换为公开模型。

```php
use Yaleksandr\Weather\Contract\CurrentWeatherProvider;
use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\Temperature;
use Yaleksandr\Weather\Value\WeatherCondition;

final class ExampleCurrentWeatherProvider implements CurrentWeatherProvider
{
    public function current(Coordinates $coordinates): CurrentWeather
    {
        // 在这里请求你的服务，并将响应转换为公开模型。
        return CurrentWeather::fromObservation(
            $coordinates,
            new \DateTimeImmutable(),
            Temperature::fromCelsius(20.0),
            WeatherCondition::Clear,
        );
    }
}
```

## 接入

将实现直接传给 `Weather` 构造函数。

```php
use Yaleksandr\Weather\Weather;
use Yaleksandr\Weather\Value\Coordinates;

$customProvider = new ExampleCurrentWeatherProvider();
$weather = new Weather($customProvider);
$current = $weather->current(Coordinates::fromDegrees(55.7558, 37.6173));
```

## 提供商职责

- 从数据源获取数据；
- 转换为 `CurrentWeather`；
- 遵守公开模型的单位和约束；
- 正确处理集成错误。

结果结构见 [`CurrentWeather` 指南](../reference/current-weather_zh.md)，错误模型见[错误指南](../reference/errors_zh.md)。
