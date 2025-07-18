<?php

declare(strict_types=1);

namespace Tizo\Dvla\VehicleEnquiry\Auth\ValueObject;

use ParagonIE\HiddenString\HiddenString;

final class ApiKey
{
    private function __construct(private readonly HiddenString $value)
    {
    }

    public static function fromString(string $token): self
    {
        return new self(new HiddenString($token, true, true));
    }

    public function toString(): string
    {
        return $this->value->getString();
    }

    public function equals(ApiKey $other): bool
    {
        return $this->value->equals($other->value);
    }
}
