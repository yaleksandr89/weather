# 提供商

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./providers.md) | [English](./providers_en.md) | [Español](./providers_es.md) | **已选择** | [Français](./providers_fr.md) | [Deutsch](./providers_de.md) |

内置支持 WeatherAPI 和 Open-Meteo。创建 `Weather` 时选择对应配置；之后 `current()` 的调用方式完全相同。两者都会返回 `CurrentWeather`，具体服务的格式、单位和天气代码会在库内部完成规范化。

## Open-Meteo

`OpenMeteoConfig` 不接收参数。内置集成不需要 API key。

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);
```

### 真实响应与规范化

真实 Open-Meteo 响应片段：

```json
{
    "time": 1786285800,
    "interval": 900,
    "temperature_2m": 21.8,
    "relative_humidity_2m": 48,
    "apparent_temperature": 20.4,
    "precipitation": 0.0,
    "weather_code": 1,
    "pressure_msl": 1018.4,
    "wind_speed_10m": 2.91,
    "wind_direction_10m": 333,
    "wind_gusts_10m": 8.1
}
```

规范化后，库返回：

```text
Yaleksandr\Weather\Model\CurrentWeather {
    coordinates: Yaleksandr\Weather\Value\Coordinates {
        latitude: 55.7558
        longitude: 37.6173
    }
    observedAt: DateTimeImmutable {
        date: 2026-08-09 14:30:00 UTC
    }
    temperature: Yaleksandr\Weather\Value\Temperature {
        celsius: 21.8
    }
    condition: Yaleksandr\Weather\Value\WeatherCondition {
        name: Clear
        value: clear
    }
    feelsLike: Yaleksandr\Weather\Value\Temperature {
        celsius: 20.4
    }
    humidityPercent: 48.0
    pressureHectopascals: 1018.4
    wind: Yaleksandr\Weather\Value\Wind {
        speed: 2.91
        directionDegrees: 333.0
        gust: 8.1
    }
    precipitationMillimeters: 0.0
}
```

Open-Meteo 已经以 m/s 返回风速和阵风，因此这些值无需单位转换即可写入 `Wind`。`time` 中的 Unix timestamp 会转换为 UTC 的 `DateTimeImmutable`，`weather_code` 会映射为 `WeatherCondition`，结果中的坐标仍然是原始请求的坐标。

## WeatherAPI

`WeatherApiConfig` 需要非空 API key。

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(
    new WeatherApiConfig('YOUR_WEATHERAPI_KEY'),
);

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);
```

将 `YOUR_WEATHERAPI_KEY` 替换为你的密钥，不要把真实密钥提交到仓库或写入日志。密钥如何存储由应用负责，例如可使用应用配置、环境变量或密钥存储。

### 真实响应与规范化

真实 WeatherAPI 响应片段：

```json
{
    "last_updated_epoch": 1786290300,
    "temp_c": 21.4,
    "condition": {
        "text": "Sunny",
        "code": 1000
    },
    "wind_kph": 12.6,
    "wind_degree": 341,
    "pressure_mb": 1019.0,
    "precip_mm": 0.0,
    "humidity": 41,
    "feelslike_c": 18.4,
    "gust_kph": 15.8
}
```

规范化后，库返回：

```text
Yaleksandr\Weather\Model\CurrentWeather {
    coordinates: Yaleksandr\Weather\Value\Coordinates {
        latitude: 55.7558
        longitude: 37.6173
    }
    observedAt: DateTimeImmutable {
        date: 2026-08-09 15:45:00 UTC
    }
    temperature: Yaleksandr\Weather\Value\Temperature {
        celsius: 21.4
    }
    condition: Yaleksandr\Weather\Value\WeatherCondition {
        name: Clear
        value: clear
    }
    feelsLike: Yaleksandr\Weather\Value\Temperature {
        celsius: 18.4
    }
    humidityPercent: 41.0
    pressureHectopascals: 1019.0
    wind: Yaleksandr\Weather\Value\Wind {
        speed: 3.5
        directionDegrees: 341.0
        gust: 4.3888888888889
    }
    precipitationMillimeters: 0.0
}
```

WeatherAPI 以 km/h 返回风速，而公开模型使用 m/s：`12.6 km/h` 会转换为 `3.5 m/s`，`15.8 km/h` 会转换为 `4.3888888888889 m/s`。`condition.code` 会映射为 `WeatherCondition`（`1000` → `clear`），`last_updated_epoch` 会转换为 UTC 的观测时间，`pressure_mb` 作为 hPa 压力使用。与 Open-Meteo 一样，`CurrentWeather` 保留原始请求坐标。

## 如何选择提供商

| 提供商 | 配置 | 需要 API key |
|---|---|---|
| Open-Meteo | `OpenMeteoConfig` | 否 |
| WeatherAPI | `WeatherApiConfig` | 是 |

两个内置提供商都返回同一个公开模型。完整结果结构、单位和可选值见 [`CurrentWeather` 指南](../reference/current-weather_zh.md)。

## 官方文档

- [WeatherAPI](https://www.weatherapi.com/docs/)
- [Open-Meteo](https://open-meteo.com/en/docs)

如果需要其他数据源，请实现[自定义提供商](custom-provider_zh.md)。
