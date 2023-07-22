# Получение прогноза погоды по названию города

Если у вас нет учетной записи в сервисе WeatherAPI - необходимо [зарегистрироваться](https://www.weatherapi.com/login.aspx) и получить API ключ, который будет использоваться при запросе к сервису.
Ссылка на [документацию](https://www.weatherapi.com/docs/) сервиса.

## Установка

`composer required yaleksandr89/weather`

## Использование

```php
use Ya\Weather\Weather;

$apiKey = '******';

$weather = new Weather($apiKey);
$result = $w->setLocation('Moscow')
            ->get();

echo $r->getCelsius() // 18.0
echo $r->getFahrenheit() // 64.4
```
