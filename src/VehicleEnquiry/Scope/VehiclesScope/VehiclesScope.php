<?php

declare(strict_types=1);

namespace Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope;

use Tizo\Dvla\VehicleEnquiry\Scope\Scope;
use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\Request\EnquiryRequest;
use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\Response\EnquiryResponse;

final class VehiclesScope extends Scope
{
    protected static function pathFragment(): string
    {
        return 'vehicles';
    }

    public function enquireDetails(EnquiryRequest $request): EnquiryResponse
    {
        $responseData = $this->sendAndDecode($request);

        return EnquiryResponse::fromArray($responseData);
    }
}
