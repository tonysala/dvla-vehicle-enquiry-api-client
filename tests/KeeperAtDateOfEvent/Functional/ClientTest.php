<?php

declare(strict_types=1);

namespace Tests\Functional\Tizo\Dvla\KeeperAtDateOfEvent;

use Tizo\Dvla\KeeperAtDateOfEvent\Auth\JwtTokenProvider;
use Tizo\Dvla\KeeperAtDateOfEvent\Auth\RefreshingJwtAuthHttpClientDecorator;
use Tizo\Dvla\KeeperAtDateOfEvent\Auth\ValueObject\JwtToken;
use Tizo\Dvla\KeeperAtDateOfEvent\Client;
use Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\Request\VehicleKeeperRequest;
use Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject\Date;
use Tizo\Dvla\KeeperAtDateOfEvent\Scope\VehicleKeeperScope\ValueObject\RegistrationNumber;
use Tizo\Dvla\VehicleEnquiry\Auth\ApiKeyAuthHttpClientDecorator;
use Tizo\Dvla\VehicleEnquiry\Auth\ValueObject\ApiKey;
use Tizo\Dvla\VehicleEnquiry\Psr18ClientDecorator;
use Nyholm\Psr7\Request as Psr7Request;
use Nyholm\Psr7\Response as Psr7Response;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Client\ClientInterface;

final class ClientTest extends TestCase
{
    private const BASE_URL = 'https://127.0.0.1:1234/kadoe/v1';

    private ClientInterface&MockObject $httpClient;

    private Client $fixture;

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = $this->createMock(ClientInterface::class);

        $psr18 = new Psr18ClientDecorator(
            $this->httpClient
        );

        $tokenProvider = new JwtTokenProvider(
            $psr18,
            new Uri(self::BASE_URL),
            'user',
            'pass'
        );

        $this->fixture = new Client(
            new ApiKeyAuthHttpClientDecorator(
                new RefreshingJwtAuthHttpClientDecorator(
                    $psr18,
                    $tokenProvider
                ),
                ApiKey::fromString('apikey')
            ),
            new Uri(self::BASE_URL)
        );
    }

    public function test_it_requests_vehicle_keeper_and_decodes_response(): void
    {
        $request = VehicleKeeperRequest::forRegistrationNumber(
            RegistrationNumber::fromString('AA19AAA'),
            'ENQ1',
            '00EV',
            Date::fromString('2023-04-01'),
            'REF123'
        );

        $call = 0;
        $this->httpClient->expects($this->exactly(2))
            ->method('sendRequest')
            ->willReturnCallback(
                function (Psr7Request $req) use (&$call): Psr7Response {
                    $call++;

                    if ($call === 1) {
                        $this->assertSame(self::BASE_URL . '/thirdparty-access/v1/authenticate', $req->getUri()->__toString());

                        return new Psr7Response(200, [], '{"token":"jwt","expires_in":0}');
                    }

                    $this->assertSame(self::BASE_URL . '/vehicle-keeper', $req->getUri()->__toString());
                    $this->assertSame('POST', $req->getMethod());
                    $req->getBody()->rewind();
                    $this->assertSame(
                        '{"enquirerId":"ENQ1","reasonCode":"00EV","eventDate":"2023-04-01","referenceNumber":"REF123","registrationNumber":"AA19AAA"}',
                        $req->getBody()->getContents()
                    );

                    return new Psr7Response(
                        200,
                        [],
                        '{"registrationNumber":"AA19AAA","make":"Honda","model":"CR-V","taxStatus":"Untaxed","keeper":{"title":"MR","firstNames":"JOE","lastName":"BLOGGS","address":{"line1":"1 TEST","postcode":"TT1 1TT"}}}'
                    );
                }
            );

        $response = $this->fixture->vehicleKeeper()->get($request);

        $this->assertSame('AA19AAA', $response->registrationNumber()?->toString());
        $this->assertSame('Honda', $response->make());
        $this->assertSame('CR-V', $response->model());
    }

    public function test_it_refreshes_the_token_when_it_expires(): void
    {
        $request = VehicleKeeperRequest::forRegistrationNumber(
            RegistrationNumber::fromString('AA19AAA'),
            'ENQ1',
            '00EV',
            Date::fromString('2023-04-01'),
            'REF123'
        );

        $call = 0;
        $this->httpClient->expects($this->exactly(4))
            ->method('sendRequest')
            ->willReturnCallback(
                function (Psr7Request $req) use (&$call): Psr7Response {
                    $call++;

                    if ($call === 1) {
                        return new Psr7Response(200, [], '{"token":"first","expires_in":0}');
                    }

                    if ($call === 2) {
                        $this->assertSame('Bearer first', $req->getHeaderLine('Authorization'));

                        return new Psr7Response(
                            200,
                            [],
                            '{"registrationNumber":"AA19AAA","make":"Honda","model":"CR-V","taxStatus":"Untaxed","keeper":{"title":"MR","firstNames":"JOE","lastName":"BLOGGS","address":{"line1":"1 TEST","postcode":"TT1 1TT"}}}'
                        );
                    }

                    if ($call === 3) {
                        return new Psr7Response(200, [], '{"token":"second","expires_in":60}');
                    }

                    $this->assertSame('Bearer second', $req->getHeaderLine('Authorization'));

                    return new Psr7Response(
                        200,
                        [],
                        '{"registrationNumber":"AA19AAA","make":"Honda","model":"CR-V","taxStatus":"Untaxed","keeper":{"title":"MR","firstNames":"JOE","lastName":"BLOGGS","address":{"line1":"1 TEST","postcode":"TT1 1TT"}}}'
                    );
                }
            );

        $this->fixture->vehicleKeeper()->get($request);
        $this->fixture->vehicleKeeper()->get($request);
    }
}
