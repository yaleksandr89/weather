# Getting Weather Forecast by City Name

If you don't have an account on the WeatherAPI service, you need to [register](https://www.weatherapi.com/login.aspx) and obtain an API key to use for making requests to the service. 
You can find the [documentation](https://www.weatherapi.com/docs/) for the service there..

## Installation

`composer required yaleksandr89/weather`

## Usage

```php
use Ya\Weather\Weather;

$apiKey = '******';

$weather = new Weather($apiKey);
$result = $w->setLocation('Moscow')
            ->get();

echo $r->getCelsius() // 18.0
echo $r->getFahrenheit() // 64.4
```

