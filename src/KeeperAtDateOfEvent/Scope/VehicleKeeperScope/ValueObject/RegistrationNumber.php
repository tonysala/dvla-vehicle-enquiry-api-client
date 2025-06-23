<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject;

use Assert\Assert;

final class RegistrationNumber
{
    private function __construct(private readonly string $value)
    {
        Assert::that($this->value)->notEmpty('Registration number should not be empty.');
    }

    public static function fromString(string $registrationNumber): self
    {
        return new self($registrationNumber);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
