<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\Request;

use Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject\ChassisVin;
use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\ValueObject\RegistrationNumber;
use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\ValueObject\Date;
use Tizo\Dvla\VehicleEnquiry\Client\PayloadRequest;
use Tizo\Dvla\VehicleEnquiry\Client\ValueObject\HttpMethod;

final class VehicleKeeperRequest implements PayloadRequest
{
    private function __construct(
        private readonly string $enquirerId,
        private readonly string $reasonCode,
        private readonly Date $eventDate,
        private readonly string $referenceNumber,
        private readonly ?RegistrationNumber $registrationNumber,
        private readonly ?ChassisVin $chassisVin,
        private readonly ?string $linkProviderId,
    ) {
    }

    public static function forRegistrationNumber(
        RegistrationNumber $registrationNumber,
        string $enquirerId,
        string $reasonCode,
        Date $eventDate,
        string $referenceNumber,
        ?string $linkProviderId = null
    ): self {
        return new self($enquirerId, $reasonCode, $eventDate, $referenceNumber, $registrationNumber, null, $linkProviderId);
    }

    public static function forChassisVin(
        ChassisVin $vin,
        string $enquirerId,
        string $reasonCode,
        Date $eventDate,
        string $referenceNumber,
        ?string $linkProviderId = null
    ): self {
        return new self($enquirerId, $reasonCode, $eventDate, $referenceNumber, null, $vin, $linkProviderId);
    }

    public function method(): HttpMethod
    {
        return HttpMethod::POST;
    }

    /**
     * @return array<string, string>
     */
    public function payload(): array
    {
        $payload = [
            'enquirerId' => $this->enquirerId,
            'reasonCode' => $this->reasonCode,
            'eventDate' => $this->eventDate->toString(),
            'referenceNumber' => $this->referenceNumber,
        ];

        if ($this->registrationNumber !== null) {
            $payload['registrationNumber'] = $this->registrationNumber->toString();
        }
        if ($this->chassisVin !== null) {
            $payload['chassisVin'] = $this->chassisVin->toString();
        }
        if ($this->linkProviderId !== null) {
            $payload['linkProviderId'] = $this->linkProviderId;
        }

        return $payload;
    }
}
