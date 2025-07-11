<?php

declare(strict_types=1);

namespace Tizo\Dvla\VehicleEnquiry\Scope;

use Tizo\Dvla\VehicleEnquiry\Client\HttpClient;
use Tizo\Dvla\VehicleEnquiry\Client\PayloadRequest;
use Tizo\Dvla\VehicleEnquiry\Client\Request;
use Tizo\Dvla\VehicleEnquiry\Client\Response;
use Psr\Http\Message\UriInterface;

abstract class Scope
{
    private readonly UriInterface $baseUri;

    public function __construct(private readonly HttpClient $client, UriInterface $baseUri)
    {
        $this->baseUri = $baseUri->withPath($baseUri->getPath().'/'.static::pathFragment());
    }

    protected function send(Request $request): Response
    {
        return $this->client()->request(
            $this->baseUri(),
            $request->method(),
            $this->payload($request)
        );
    }

    /**
     * @return array<mixed>
     */
    protected function sendAndDecode(Request $request): array
    {
        return $this->send($request)->content()->decode();
    }

    /**
     * @return array<string|int, mixed>|null
     */
    private function payload(Request $request): ?array
    {
        return $request instanceof PayloadRequest
            ? $request->payload()
            : null;
    }

    final protected function baseUri(): UriInterface
    {
        return $this->baseUri;
    }

    final protected function client(): HttpClient
    {
        return $this->client;
    }

    abstract protected static function pathFragment(): string;
}
