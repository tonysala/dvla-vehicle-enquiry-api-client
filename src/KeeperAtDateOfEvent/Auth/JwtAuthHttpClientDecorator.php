<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Auth;

use Tizo\Dvla\KeeperAtDateOfEvent\Auth\ValueObject\JwtToken;
use Tizo\Dvla\VehicleEnquiry\Client\HttpClient;
use Tizo\Dvla\VehicleEnquiry\Client\Response;
use Tizo\Dvla\VehicleEnquiry\Client\ValueObject\HttpMethod;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\UriInterface;

final class JwtAuthHttpClientDecorator implements HttpClient
{
    public function __construct(private readonly HttpClient $innerClient, private readonly JwtToken $token)
    {
    }

    /**
     * @param array<string|int, mixed>|null $data
     * @param array<string, string|array<string>> $headers
     */
    public function request(UriInterface $uri, HttpMethod $method, ?array $data = null, array $headers = []): Response
    {
        return $this->innerClient->request(
            $uri,
            $method,
            $data,
            [
                'Authorization' => 'Bearer ' . $this->token->toString(),
                ...$headers,
            ]
        );
    }

    /**
     * @param array<string|int, mixed>|null $data
     * @param array<string, string|array<string>> $headers
     */
    public function requestAsync(UriInterface $uri, HttpMethod $method, ?array $data = null, array $headers = []): \GuzzleHttp\Promise\PromiseInterface
    {
        return $this->innerClient->requestAsync(
            $uri,
            $method,
            $data,
            [
                'Authorization' => 'Bearer ' . $this->token->toString(),
                ...$headers,
            ]
        );
    }
}
