<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject;

use Assert\Assert;

final class Date
{
    private function __construct(private readonly string $date)
    {
        Assert::that($this->date)
            ->date('Y-m-d');
    }

    public static function fromString(string $date): self
    {
        return new self($date);
    }

    public function toDateTime(): \DateTimeImmutable
    {
        $date = new \DateTimeImmutable($this->date);

        return $date->setTime(0, 0);
    }

    public function toString(): string
    {
        return $this->date;
    }
}
