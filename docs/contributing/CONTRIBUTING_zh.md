# 参与贡献

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/CONTRIBUTING.md) | [English](./CONTRIBUTING_en.md) | [Español](./CONTRIBUTING_es.md) | **中文** | [Français](./CONTRIBUTING_fr.md) | [Deutsch](./CONTRIBUTING_de.md) |

感谢你愿意改进 Weather。本指南将帮助你准备更易审查和维护的更改。

## 开始之前

- 请通过 GitHub Issue 报告可复现的错误。
- 如需新增功能或改进，请创建 feature request。
- 对于安全问题，请遵循[安全政策](../../.github/SECURITY.md)，不要公开敏感细节。
- 对公共 API 或提供程序契约进行重大更改或破坏向后兼容性的更改之前，请先在 Issue 中与维护者讨论。

## 包契约

- 本包根据 `Coordinates` 获取当前天气，并返回 `CurrentWeather`。
- 内置的 Open-Meteo 和 WeatherAPI 提供程序会将不同的响应格式转换为相同的 `CurrentWeather`。
- 共享的公共行为必须保持独立于所选提供程序。
- 如果更改影响共享的标准化逻辑或不变量，请验证每个受影响提供程序的相关行为。
- 自定义提供程序实现 `CurrentWeatherProvider`。
- 公共 API 更改必须是有意的，并考虑 SemVer 和向后兼容性。
- 不要添加与当前问题无关的抽象或功能。

## 分支

使用能反映更改目的的简短名称，例如：

```text
feature/add-provider
fix/weatherapi-condition-mapping
docs/update-provider-guide
```

## 提交

建议使用 Conventional Commits 格式。例如：

```text
feat: add provider integration
fix: normalize weather condition
docs: clarify provider configuration
test: cover malformed provider response
chore: update CI configuration
```

## 本地检查

安装依赖并运行完整检查：

```shell
composer install
composer check
```

还可以运行以下针对性检查：

```shell
composer test
composer analyse
composer cs:check
```

需要覆盖率报告时，可以单独运行 `composer coverage`；并非每项更改都必须运行它。

## Pull Request

在 Pull Request 描述中说明：

- 问题和所做的更改；
- 已执行的检查；
- 对公共 API 或提供程序行为的影响；
- 新增或更新的测试；
- 文档更改；
- 如果这些政策发生更改，CONTRIBUTING 和 SECURITY 的翻译是否已同步。

提交前请确保：

- 未添加真实的 WeatherAPI 密钥、令牌、其他机密或私有配置；
- 代码、日志、Issues 或测试数据中没有包含私有数据的生产系统响应；
- 测试 fixtures 是合成数据，并已清除敏感信息；
- 未将 `vendor/`、生成的缓存和覆盖率输出添加到仓库。
