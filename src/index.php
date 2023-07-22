<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Ya\Weather\Weather;

try {
    $w = new Weather('e145dbfe35544b848f381018232107');
    $r = $w->setLocation('Moscow')
        ->get();

    dump($r->getCelsius());
    dump($r->getFahrenheit());
} catch (Throwable $e) {
    dd($e);
}
