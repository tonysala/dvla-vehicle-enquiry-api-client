<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Auth;

use Tizo\Dvla\VehicleEnquiry\Client\HttpClient;
use Tizo\Dvla\VehicleEnquiry\Client\Response;
use Tizo\Dvla\VehicleEnquiry\Client\ValueObject\HttpMethod;
use Psr\Http\Message\UriInterface;

final class RefreshingJwtAuthHttpClientDecorator implements HttpClient
{
    public function __construct(
        private readonly HttpClient $innerClient,
        private readonly JwtTokenProvider $tokenProvider,
    ) {
    }

    /**
     * @param array<string|int, mixed>|null $data
     * @param array<string, string|array<string>> $headers
     */
    public function request(UriInterface $uri, HttpMethod $method, ?array $data = null, array $headers = []): Response
    {
        $token = $this->tokenProvider->token();

        return $this->innerClient->request(
            $uri,
            $method,
            $data,
            [
                'Authorization' => 'Bearer ' . $token->toString(),
                ...$headers,
            ]
        );
    }
}
