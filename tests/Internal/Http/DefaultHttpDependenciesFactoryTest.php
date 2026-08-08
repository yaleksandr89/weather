<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Internal\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Internal\Http\DefaultHttpDependenciesFactory;

final class DefaultHttpDependenciesFactoryTest extends TestCase
{
    #[TestDox('Создаёт HTTP-зависимости с реализацией Guzzle по умолчанию')]
    public function testItCreatesDefaultGuzzleDependencies(): void
    {
        $dependencies = DefaultHttpDependenciesFactory::create();

        self::assertInstanceOf(Client::class, $dependencies->client());
        self::assertInstanceOf(HttpFactory::class, $dependencies->requestFactory());
        self::assertInstanceOf(HttpFactory::class, $dependencies->uriFactory());
    }
}
