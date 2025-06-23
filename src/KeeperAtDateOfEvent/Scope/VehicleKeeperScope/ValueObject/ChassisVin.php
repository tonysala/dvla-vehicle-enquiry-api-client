<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject;

use Assert\Assert;

final class ChassisVin
{
    private function __construct(private readonly string $value)
    {
        Assert::that($this->value)->notEmpty('VIN should not be empty.');
    }

    public static function fromString(string $vin): self
    {
        return new self($vin);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
