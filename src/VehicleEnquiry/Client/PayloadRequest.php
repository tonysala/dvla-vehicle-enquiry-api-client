<?php

declare(strict_types=1);

namespace Tizo\Dvla\VehicleEnquiry\Client;

interface PayloadRequest extends Request
{
    /**
     * @return array<string|int, mixed>
     */
    public function payload(): array;
}
