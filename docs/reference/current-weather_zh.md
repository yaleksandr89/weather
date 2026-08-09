# CurrentWeather

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./current-weather.md) | [English](./current-weather_en.md) | [Español](./current-weather_es.md) | **已选择** | [Français](./current-weather_fr.md) | [Deutsch](./current-weather_de.md) |

`Weather::current()` 返回 `CurrentWeather`。这个强类型模型包含请求坐标、观测时间以及经过规范化的天气数据。

## `CurrentWeather` 字段

| 数据 | 方法 | 类型 | 单位 | 可以为 `null` |
|---|---|---|---|---|
| 坐标 | `coordinates()` | `Coordinates` | 度 | 否 |
| 观测时间 | `observedAt()` | `DateTimeImmutable` | — | 否 |
| 温度 | `temperature()` | `Temperature` | °C | 否 |
| 天气状态 | `condition()` | `WeatherCondition` | — | 否 |
| 体感温度 | `feelsLike()` | `Temperature` | °C | 是 |
| 湿度 | `humidityPercent()` | `float` | % | 是 |
| 气压 | `pressureHectopascals()` | `float` | hPa | 是 |
| 风 | `wind()` | `Wind` | m/s，度 | 是 |
| 降水 | `precipitationMillimeters()` | `float` | mm | 是 |

可选字段可能不会出现在某个提供商的响应中。使用前请检查是否为 `null`。

## 坐标

`Coordinates::fromDegrees($latitude, $longitude)` 使用以度为单位的纬度和经度创建对象。纬度必须在 `[-90, 90]`，经度必须在 `[-180, 180]`；两个值都必须是有限数。

```php
$coordinates = $current->coordinates();
echo $coordinates->latitude;
echo $coordinates->longitude;
```

## 观测时间

`observedAt()` 返回 `DateTimeImmutable`，表示提供商数据中的观测时间。

```php
$observedAt = $current->observedAt();
```

## 温度

`temperature()` 始终返回以 °C 表示的 `Temperature`。`feelsLike()` 返回 `?Temperature`，可能为 `null`。

```php
$celsius = $current->temperature()->celsius();
$fahrenheit = $current->temperature()->fahrenheit();
$feelsLike = $current->feelsLike()?->celsius();
```

`fahrenheit()` 会进行额外转换；`Temperature` 的基础值以 °C 存储。

## 湿度和气压

湿度以百分比表示，气压以 hPa 表示。

```php
$humidity = $current->humidityPercent();
$pressure = $current->pressureHectopascals();
```

## 风

`wind()` 返回 `?Wind`。如果有风数据，风速以 m/s 表示；风向和阵风可能为 `null`。风向范围为 `[0, 360)` 度，阵风单位为 m/s。

```php
if (($wind = $current->wind()) !== null) {
    $speed = $wind->speedMetersPerSecond();
    $direction = $wind->directionDegrees();
    $gust = $wind->gustMetersPerSecond();
}
```

## 降水

`precipitationMillimeters()` 返回以 mm 表示的降水量，或 `null`。

## 天气状态

`condition()` 返回 `WeatherCondition` enum。其字符串值（`$current->condition()->value`）是以下 enum case 之一：

```text
clear
partly_cloudy
cloudy
fog
drizzle
rain
snow
sleet
thunderstorm
unknown
```

输入验证和外部请求错误见[错误指南](errors_zh.md)。
