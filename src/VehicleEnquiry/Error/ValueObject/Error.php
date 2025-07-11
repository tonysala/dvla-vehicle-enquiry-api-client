<?php

declare(strict_types=1);

namespace Tizo\Dvla\VehicleEnquiry\Error\ValueObject;

use Assert\Assert;

final class Error
{
    private function __construct(
        private readonly string $status,
        private readonly string $code,
        private readonly string $title,
        private readonly string $detail
    ) {
    }

    public static function fromArray(array $data): self
    {
        Assert::that($data)
            ->keyExists('status')
            ->keyExists('code')
            ->keyExists('title')
            ->keyExists('detail');

        return new self(
            $data['status'],
            $data['code'],
            $data['title'],
            $data['detail']
        );
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }
}
