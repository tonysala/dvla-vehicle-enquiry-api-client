<?php

declare(strict_types=1);

namespace Tizo\Dvla\VehicleEnquiry\Client;

use Tizo\Dvla\VehicleEnquiry\Client\ValueObject\HttpMethod;

interface Request
{
    public function method(): HttpMethod;
}
