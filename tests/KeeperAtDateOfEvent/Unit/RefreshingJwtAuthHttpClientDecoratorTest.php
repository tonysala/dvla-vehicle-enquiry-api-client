<?php

declare(strict_types=1);

namespace Tests\Unit\Tizo\Dvla\KeeperAtDateOfEvent;

use Tizo\Dvla\KeeperAtDateOfEvent\Auth\RefreshingJwtAuthHttpClientDecorator;
use Tizo\Dvla\KeeperAtDateOfEvent\Auth\JwtTokenProvider;
use Tizo\Dvla\VehicleEnquiry\Client\HttpClient;
use Tizo\Dvla\VehicleEnquiry\Client\Response;
use Tizo\Dvla\VehicleEnquiry\Client\ValueObject\Content;
use Tizo\Dvla\VehicleEnquiry\Client\ValueObject\HttpMethod;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\UriInterface;

final class RefreshingJwtAuthHttpClientDecoratorTest extends TestCase
{
    private HttpClient&MockObject $innerClient;
    private RefreshingJwtAuthHttpClientDecorator $fixture;

    protected function setUp(): void
    {
        parent::setUp();

        $this->innerClient = $this->createMock(HttpClient::class);

        $providerHttp = new class() implements HttpClient {
            public int $call = 0;

            public function request(UriInterface $uri, HttpMethod $method, ?array $data = null, array $headers = []): Response
            {
                $this->call++;
                $token = $this->call === 1 ? 'first' : 'second';

                return Response::with(200, [], Content::fromString('{"token":"' . $token . '","expires_in":0}'));
            }
        };

        $provider = new JwtTokenProvider(
            $providerHttp,
            new Uri('https://example.com'),
            'user',
            'pass'
        );

        $this->fixture = new RefreshingJwtAuthHttpClientDecorator(
            $this->innerClient,
            $provider
        );
    }

    public function test_it_fetches_the_token_before_every_request(): void
    {
        $call = 0;
        $this->innerClient->expects($this->exactly(2))
            ->method('request')
            ->willReturnCallback(
                function (UriInterface $uri, HttpMethod $method, ?array $data, array $headers) use (&$call): Response {
                    $call++;
                    $expected = $call === 1 ? 'first' : 'second';
                    $this->assertSame('Bearer ' . $expected, $headers['Authorization']);

                    return Response::with(200, [], Content::empty());
                }
            );

        $uri = new Uri('https://example.com/test');

        $this->fixture->request($uri, HttpMethod::GET);
        $this->fixture->request($uri, HttpMethod::GET);
    }
}
