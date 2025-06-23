<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent;

use Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\VehicleKeeperScope;
use Tizo\Dvla\VehicleEnquiry\Client\HttpClient;
use Psr\Http\Message\UriInterface;

final class Client
{
    public function __construct(private readonly HttpClient $httpClient, private readonly UriInterface $uri)
    {
    }

    public function vehicleKeeper(): VehicleKeeperScope
    {
        return new VehicleKeeperScope($this->httpClient, $this->uri);
    }
}
