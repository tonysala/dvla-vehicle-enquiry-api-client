<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject;

final class Address
{
    private function __construct(
        private readonly ?string $line1,
        private readonly ?string $line2,
        private readonly ?string $line3,
        private readonly ?string $line4,
        private readonly ?string $line5,
        private readonly ?string $postcode,
    ) {
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['line1'] ?? null,
            $data['line2'] ?? null,
            $data['line3'] ?? null,
            $data['line4'] ?? null,
            $data['line5'] ?? null,
            $data['postcode'] ?? null,
        );
    }

    public function line1(): ?string { return $this->line1; }
    public function line2(): ?string { return $this->line2; }
    public function line3(): ?string { return $this->line3; }
    public function line4(): ?string { return $this->line4; }
    public function line5(): ?string { return $this->line5; }
    public function postcode(): ?string { return $this->postcode; }
}
