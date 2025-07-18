<?php

declare(strict_types=1);

namespace Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\Response;

use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\ValueObject\Date;
use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\ValueObject\MotStatus;
use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\ValueObject\RegistrationNumber;
use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\ValueObject\TaxStatus;
use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\ValueObject\YearAndMonth;
use Assert\Assert;

final class EnquiryResponse
{
    private RegistrationNumber $registrationNumber;

    private ?TaxStatus $taxStatus = null;

    private ?Date $taxDueDate = null;

    private ?MotStatus $motStatus = null;

    private ?Date $motExpiryDate = null;

    private ?string $make = null;

    private ?YearAndMonth $monthOfFirstDvlaRegistration = null;

    private ?YearAndMonth $monthOfFirstRegistration = null;

    private ?int $yearOfManufacture = null;

    private ?int $engineCapacity = null;

    private ?int $co2Emissions = null;

    private ?string $fuelType = null;

    private ?bool $markedForExport = null;

    private ?string $colour = null;

    private ?string $typeApproval = null;

    private ?string $wheelplan = null;

    private ?int $revenueWeight = null;

    private ?string $realDrivingEmissions = null;

    private ?Date $dateOfLastV5CIssued = null;

    private ?string $euroStatus = null;

    private function __construct()
    {
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): self
    {
        Assert::that($data)
            ->keyExists('registrationNumber');

        $instance = new self();
        $instance->registrationNumber = RegistrationNumber::fromString($data['registrationNumber']);
        $instance->taxStatus = isset($data['taxStatus']) ? TaxStatus::from($data['taxStatus']) : null;
        $instance->taxDueDate = isset($data['taxDueDate']) ? Date::fromString($data['taxDueDate']) : null;
        $instance->motStatus = isset($data['motStatus']) ? MotStatus::from($data['motStatus']) : null;
        $instance->motExpiryDate = isset($data['motExpiryDate']) ? Date::fromString($data['motExpiryDate']) : null;
        $instance->make = $data['make'] ?? null;
        $instance->monthOfFirstDvlaRegistration = isset($data['monthOfFirstDvlaRegistration']) ? YearAndMonth::fromString($data['monthOfFirstDvlaRegistration']) : null;
        $instance->monthOfFirstRegistration = isset($data['monthOfFirstRegistration']) ? YearAndMonth::fromString($data['monthOfFirstRegistration']) : null;
        $instance->yearOfManufacture = $data['yearOfManufacture'] ?? null;
        $instance->engineCapacity = $data['engineCapacity'] ?? null;
        $instance->co2Emissions = $data['co2Emissions'] ?? null;
        $instance->fuelType = $data['fuelType'] ?? null;
        $instance->markedForExport = $data['markedForExport'] ?? null;
        $instance->colour = $data['colour'] ?? null;
        $instance->typeApproval = $data['typeApproval'] ?? null;
        $instance->wheelplan = $data['wheelplan'] ?? null;
        $instance->revenueWeight = $data['revenueWeight'] ?? null;
        $instance->realDrivingEmissions = $data['realDrivingEmissions'] ?? null;
        $instance->dateOfLastV5CIssued = isset($data['dateOfLastV5CIssued']) ? Date::fromString($data['dateOfLastV5CIssued']) : null;
        $instance->euroStatus = $data['euroStatus'] ?? null;

        return $instance;
    }

    public function getRegistrationNumber(): RegistrationNumber
    {
        return $this->registrationNumber;
    }

    public function getTaxStatus(): ?TaxStatus
    {
        return $this->taxStatus;
    }

    public function getTaxDueDate(): ?Date
    {
        return $this->taxDueDate;
    }

    public function getMotStatus(): ?MotStatus
    {
        return $this->motStatus;
    }

    public function getMotExpiryDate(): ?Date
    {
        return $this->motExpiryDate;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function getMonthOfFirstDvlaRegistration(): ?YearAndMonth
    {
        return $this->monthOfFirstDvlaRegistration;
    }

    public function getMonthOfFirstRegistration(): ?YearAndMonth
    {
        return $this->monthOfFirstRegistration;
    }

    public function getYearOfManufacture(): ?int
    {
        return $this->yearOfManufacture;
    }

    public function getEngineCapacity(): ?int
    {
        return $this->engineCapacity;
    }

    public function getCo2Emissions(): ?int
    {
        return $this->co2Emissions;
    }

    public function getFuelType(): ?string
    {
        return $this->fuelType;
    }

    public function getMarkedForExport(): ?bool
    {
        return $this->markedForExport;
    }

    public function getColour(): ?string
    {
        return $this->colour;
    }

    public function getTypeApproval(): ?string
    {
        return $this->typeApproval;
    }

    public function getWheelplan(): ?string
    {
        return $this->wheelplan;
    }

    public function getRevenueWeight(): ?int
    {
        return $this->revenueWeight;
    }

    public function getRealDrivingEmissions(): ?string
    {
        return $this->realDrivingEmissions;
    }

    public function getDateOfLastV5CIssued(): ?Date
    {
        return $this->dateOfLastV5CIssued;
    }

    public function getEuroStatus(): ?string
    {
        return $this->euroStatus;
    }
}
