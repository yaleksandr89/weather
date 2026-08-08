<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Internal\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;

final class DefaultHttpDependenciesFactory
{
    public static function create(): HttpDependencies
    {
        $httpFactory = new HttpFactory();

        return new HttpDependencies(
            new Client(),
            $httpFactory,
            $httpFactory,
        );
    }
}
