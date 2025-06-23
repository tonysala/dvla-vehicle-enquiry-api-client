<?php

declare(strict_types=1);

namespace App\Services\Dvla\VehicleEnquiry;

use App\Services\Dvla\VehicleEnquiry\Client\HttpClient;
use App\Services\Dvla\VehicleEnquiry\Scope\VehiclesScope\VehiclesScope;
use Psr\Http\Message\UriInterface;

final class Client
{
    public function __construct(private readonly HttpClient $httpClient, private readonly UriInterface $uri)
    {
    }

    public function vehicles(): VehiclesScope
    {
        return new VehiclesScope($this->httpClient, $this->uri);
    }
}
