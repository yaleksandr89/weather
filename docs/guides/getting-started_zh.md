# 入门

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./getting-started.md) | [English](./getting-started_en.md) | [Español](./getting-started_es.md) | **已选择** | [Français](./getting-started_fr.md) | [Deutsch](./getting-started_de.md) |

本指南介绍安装、选择内置提供商、创建坐标，以及第一次请求当前天气。

## 要求

需要 `PHP ^8.4` 和 `Composer`。

## 安装

```bash
composer require yaleksandr89/weather
```

## 选择提供商

最简单的起点是 Open-Meteo：内置集成不需要 API key。两种方式都通过 `Weather::create()` 创建 `Weather`。

### Open-Meteo

`OpenMeteoConfig` 不接收参数。

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());
```

### WeatherAPI

`WeatherApiConfig` 需要 API key。

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(
    new WeatherApiConfig('YOUR_WEATHERAPI_KEY'),
);
```

将 `YOUR_WEATHERAPI_KEY` 替换为你的密钥，不要把真实密钥提交到仓库。密钥如何存储由应用负责，例如可使用应用配置、环境变量或密钥存储。

详细比较和服务文档链接见[提供商指南](providers_zh.md)。

## 坐标

使用以度为单位的纬度和经度创建 `Coordinates`。纬度必须在 `[-90, 90]`，经度必须在 `[-180, 180]`；两个值都必须是有限数。

```php
use Yaleksandr\Weather\Value\Coordinates;

$coordinates = Coordinates::fromDegrees(55.7558, 37.6173);
```

## 获取当前天气

将坐标传给 `current()`。

```php
$current = $weather->current($coordinates);

echo $current->temperature()->celsius();
echo $current->condition()->value;
```

结果字段和可选值见 [`CurrentWeather` 指南](../reference/current-weather_zh.md)。输入数据和服务请求错误见[错误指南](../reference/errors_zh.md)。
