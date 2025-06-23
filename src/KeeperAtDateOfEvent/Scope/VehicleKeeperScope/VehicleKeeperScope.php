<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope;

use Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\Request\VehicleKeeperRequest;
use Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\Response\VehicleKeeperResponse;
use Tizo\Dvla\VehicleEnquiry\Scope\Scope;

final class VehicleKeeperScope extends Scope
{
    protected static function pathFragment(): string
    {
        return 'vehicle-keeper';
    }

    public function get(VehicleKeeperRequest $request): VehicleKeeperResponse
    {
        $responseData = $this->sendAndDecode($request);

        return VehicleKeeperResponse::fromArray($responseData);
    }
}
