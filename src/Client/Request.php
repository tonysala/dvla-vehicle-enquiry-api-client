<?php

declare(strict_types=1);

namespace App\Services\Dvla\VehicleEnquiry\Client;

use App\Services\Dvla\VehicleEnquiry\Client\ValueObject\HttpMethod;

interface Request
{
    public function method(): HttpMethod;
}
