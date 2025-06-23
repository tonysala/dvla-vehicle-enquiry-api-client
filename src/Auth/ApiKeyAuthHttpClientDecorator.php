<?php

declare(strict_types=1);

namespace App\Services\Dvla\VehicleEnquiry\Auth;

use App\Services\Dvla\VehicleEnquiry\Auth\ValueObject\ApiKey;
use App\Services\Dvla\VehicleEnquiry\Client\HttpClient;
use App\Services\Dvla\VehicleEnquiry\Client\Response;
use App\Services\Dvla\VehicleEnquiry\Client\ValueObject\HttpMethod;
use Psr\Http\Message\UriInterface;

final class ApiKeyAuthHttpClientDecorator implements HttpClient
{
    public function __construct(private readonly HttpClient $innerClient, private readonly ApiKey $apiKey)
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
                'x-api-key' => $this->apiKey->toString(),
                ...$headers,
            ]
        );
    }
}
