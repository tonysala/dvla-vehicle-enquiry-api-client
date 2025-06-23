<?php

declare(strict_types=1);

namespace Tests\Unit\Tizo\Dvla\KeeperAtDateOfEvent;

use Tizo\Dvla\KeeperAtDateOfEvent\Auth\JwtTokenProvider;
use Tizo\Dvla\VehicleEnquiry\Client\HttpClient;
use Tizo\Dvla\VehicleEnquiry\Client\Response;
use Tizo\Dvla\VehicleEnquiry\Client\ValueObject\Content;
use Tizo\Dvla\VehicleEnquiry\Client\ValueObject\HttpMethod;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class JwtTokenProviderTest extends TestCase
{
    private HttpClient&MockObject $httpClient;
    private JwtTokenProvider $fixture;

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = $this->createMock(HttpClient::class);
        $this->fixture = new JwtTokenProvider(
            $this->httpClient,
            new Uri('https://example.com'),
            'user',
            'pass'
        );
    }

    public function test_it_refreshes_when_token_is_expired(): void
    {
        $this->httpClient->expects($this->exactly(2))
            ->method('request')
            ->with(
                $this->callback(fn($uri) => (string) $uri === 'https://example.com/thirdparty-access/v1/authenticate'),
                HttpMethod::POST,
                ['username' => 'user', 'password' => 'pass']
            )
            ->willReturnOnConsecutiveCalls(
                Response::with(200, [], Content::fromString('{"token":"first","expires_in":0}')),
                Response::with(200, [], Content::fromString('{"token":"second","expires_in":60}'))
            );

        $first = $this->fixture->token();
        $second = $this->fixture->token();

        $this->assertSame('first', $first->toString());
        $this->assertSame('second', $second->toString());
    }
}
