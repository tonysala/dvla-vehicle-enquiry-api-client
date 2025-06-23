<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Auth\ValueObject;

use ParagonIE\HiddenString\HiddenString;

final class JwtToken
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
}
