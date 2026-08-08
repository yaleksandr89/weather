<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Internal\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

final readonly class HttpDependencies
{
    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private UriFactoryInterface $uriFactory,
    ) {}

    public function client(): ClientInterface
    {
        return $this->client;
    }

    public function requestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    public function uriFactory(): UriFactoryInterface
    {
        return $this->uriFactory;
    }
}
