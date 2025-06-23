<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\Response;

use Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject\Address;
use Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject\Keeper;
use Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject\ChassisVin;
use Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject\RegistrationNumber;
use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\ValueObject\TaxStatus;
use Assert\Assert;

final class VehicleKeeperResponse
{
    private ?RegistrationNumber $registrationNumber = null;
    private ?ChassisVin $chassisVin = null;
    private ?string $make = null;
    private ?string $model = null;
    private ?string $colour = null;
    private ?string $secondaryColour = null;
    private ?Keeper $keeper = null;
    private ?string $message = null;
    private ?TaxStatus $taxStatus = null;
    private ?int $seatingCapacity = null;
    private ?string $bodyType = null;
    private ?string $taxClass = null;
    private ?string $fleetNumber = null;

    private function __construct()
    {
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): self
    {
        Assert::that($data)->keyExists('keeper');

        $self = new self();
        if (isset($data['registrationNumber'])) {
            $self->registrationNumber = RegistrationNumber::fromString($data['registrationNumber']);
        }
        if (isset($data['chassisVin'])) {
            $self->chassisVin = ChassisVin::fromString($data['chassisVin']);
        }
        $self->make = $data['make'] ?? null;
        $self->model = $data['model'] ?? null;
        $self->colour = $data['colour'] ?? null;
        $self->secondaryColour = $data['secondaryColour'] ?? null;
        $self->message = $data['message'] ?? null;
        $self->keeper = Keeper::fromArray($data['keeper']);
        $self->taxStatus = isset($data['taxStatus']) ? TaxStatus::from($data['taxStatus']) : null;
        $self->seatingCapacity = isset($data['seatingCapacity']) ? (int) $data['seatingCapacity'] : null;
        $self->bodyType = $data['bodyType'] ?? null;
        $self->taxClass = $data['taxClass'] ?? null;
        $self->fleetNumber = $data['fleetNumber'] ?? null;

        return $self;
    }

    public function registrationNumber(): ?RegistrationNumber { return $this->registrationNumber; }
    public function chassisVin(): ?ChassisVin { return $this->chassisVin; }
    public function make(): ?string { return $this->make; }
    public function model(): ?string { return $this->model; }
    public function colour(): ?string { return $this->colour; }
    public function secondaryColour(): ?string { return $this->secondaryColour; }
    public function keeper(): ?Keeper { return $this->keeper; }
    public function message(): ?string { return $this->message; }
    public function taxStatus(): ?TaxStatus { return $this->taxStatus; }
    public function seatingCapacity(): ?int { return $this->seatingCapacity; }
    public function bodyType(): ?string { return $this->bodyType; }
    public function taxClass(): ?string { return $this->taxClass; }
    public function fleetNumber(): ?string { return $this->fleetNumber; }
}
