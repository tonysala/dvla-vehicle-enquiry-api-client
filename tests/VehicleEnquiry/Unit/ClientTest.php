<?php

declare(strict_types=1);

namespace Tests\Unit\Tizo\Dvla\VehicleEnquiry;

use Tizo\Dvla\VehicleEnquiry\Client;
use Tizo\Dvla\VehicleEnquiry\Client\HttpClient;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

final class ClientTest extends TestCase
{
    private HttpClient&MockObject $httpClient;

    private UriInterface&MockObject $baseUri;

    private Client $fixture;

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = $this->createMock(HttpClient::class);
        $this->baseUri = $this->createMock(UriInterface::class);

        $this->fixture = new Client(
            $this->httpClient,
            $this->baseUri
        );
    }

    #[Test]
    public function it_should_create_the_vehicles_scope_with_its_own_path(): void
    {
        $this->baseUri->method('getPath')
            ->willReturn('/aaa/bbb/ccc');

        $this->baseUri
            ->expects($this->once())
            ->method('withPath')
            ->with('/aaa/bbb/ccc/vehicles')
            ->willReturn($this->createMock(UriInterface::class));

        $this->fixture->vehicles();
    }
}
