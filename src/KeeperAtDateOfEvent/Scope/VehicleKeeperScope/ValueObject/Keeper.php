<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject;

final class Keeper
{
    private function __construct(
        private readonly ?string $title,
        private readonly ?string $firstNames,
        private readonly ?string $lastName,
        private readonly ?string $companyName,
        private readonly ?Address $address,
    ) {
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'] ?? null,
            $data['firstNames'] ?? null,
            $data['lastName'] ?? null,
            $data['companyName'] ?? null,
            isset($data['address']) ? Address::fromArray($data['address']) : null,
        );
    }

    public function title(): ?string { return $this->title; }
    public function firstNames(): ?string { return $this->firstNames; }
    public function lastName(): ?string { return $this->lastName; }
    public function companyName(): ?string { return $this->companyName; }
    public function address(): ?Address { return $this->address; }
}
