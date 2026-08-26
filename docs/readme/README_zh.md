# Weather

[![Source Code](https://img.shields.io/badge/source-yaleksandr89%2Fweather-blue.svg?style=flat-square)](https://github.com/yaleksandr89/weather)
[![Latest Stable Version](https://img.shields.io/packagist/v/yaleksandr89/weather.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/weather)
[![Total Downloads](https://img.shields.io/packagist/dt/yaleksandr89/weather.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/weather)
[![PHP](https://img.shields.io/badge/PHP-%5E8.4-777BB4.svg?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![guzzlehttp/guzzle](https://img.shields.io/badge/guzzlehttp%2Fguzzle-%5E8.0.1-4E5D94.svg?style=flat-square)](https://packagist.org/packages/guzzlehttp/guzzle)
[![CI](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml/badge.svg)](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](../../LICENSE)

<p align="center">
  <img
    src="../assets/weather-readme-cover.png"
    alt="Weather — current weather PHP package with unified provider normalization"
    width="100%"
  >
</p>

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../README.md) | [English](./README_en.md) | [Español](./README_es.md) | **已选择** | [Français](./README_fr.md) | [Deutsch](./README_de.md) |

用于通过坐标从 WeatherAPI 和 Open-Meteo 获取当前天气的 PHP 库。
不同服务的数据会被规范化为统一的强类型格式和统一单位。

## 这个包解决什么问题

WeatherAPI 和 Open-Meteo 使用不同的 HTTP API 和响应格式。Weather 提供统一的 PHP API，通过坐标获取当前天气，因此应用代码无需处理各个服务的差异。

## 包的功能

- 按纬度和经度获取当前天气；
- 支持 WeatherAPI 和 Open-Meteo；
- 以 `CurrentWeather` 返回结果；
- 将天气数据规范化为统一的公制单位；
- 通过 `WeatherCondition` 提供强类型天气状态；
- 可通过 `CurrentWeatherProvider` 接入自定义数据源。

## 要求

- `PHP ^8.4`;
- `Composer`.

## 快速开始

安装包：

```bash
composer require yaleksandr89/weather
```

### Open-Meteo

Open-Meteo 不需要 API key。

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);

echo $current->temperature()->celsius();
echo $current->condition()->value;
```

<details>
<summary>显示真实的 Open-Meteo 响应和 CurrentWeather</summary>

#### 真实 Open-Meteo 响应片段

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

#### 规范化后的结果

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

</details>

### WeatherAPI

WeatherAPI 需要 API key：

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

将 `YOUR_WEATHERAPI_KEY` 替换为你的密钥。不要把真实密钥提交到仓库；应将其保存在源码之外，例如应用配置、环境变量或密钥存储中。

<details>
<summary>显示真实的 WeatherAPI 响应和 CurrentWeather</summary>

#### 真实 WeatherAPI 响应片段

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

#### 规范化后的结果

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

</details>

两个提供商都会返回 `CurrentWeather`。完整的数据结构、单位、可选值和对象使用示例见 [`CurrentWeather` 指南](../reference/current-weather_zh.md)。内置服务的配置及差异见[提供商指南](../guides/providers_zh.md)。

## 错误处理

输入错误与调用天气服务时产生的错误相互分离。

处理规则和异常层级见[错误指南](../reference/errors_zh.md)。

## 自定义提供商

如果 WeatherAPI 和 Open-Meteo 不适合你的需求，可以实现 `CurrentWeatherProvider` 并直接传入：

```php
use Yaleksandr\Weather\Weather;

$weather = new Weather($customProvider);
```

接口约定和实现示例见[自定义提供商指南](../guides/custom-provider_zh.md)。

## 限制

- 仅支持当前天气；
- 必须事先知道坐标；
- 不提供城市搜索或地理编码；
- 不会自动在提供商之间切换；
- 不会自动执行 retry 或 cache。

## 反馈

- 可复现的问题 — [GitHub Issues](https://github.com/yaleksandr89/weather/issues)。

---

<p align="center">
  如果这个包对你有帮助，请在 GitHub 上点个 Star，让更多开发者更容易发现它。🤘
</p>
