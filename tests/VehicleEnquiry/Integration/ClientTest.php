<?php

declare(strict_types=1);

namespace Tests\Integration\Tizo\Dvla\VehicleEnquiry;

use Tizo\Dvla\VehicleEnquiry\Auth\ApiKeyAuthHttpClientDecorator;
use Tizo\Dvla\VehicleEnquiry\Auth\ValueObject\ApiKey;
use Tizo\Dvla\VehicleEnquiry\Client;
use Tizo\Dvla\VehicleEnquiry\Psr18ClientDecorator;
use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\Request\EnquiryRequest;
use Tizo\Dvla\VehicleEnquiry\Scope\VehiclesScope\ValueObject\RegistrationNumber;
use Assert\Assert;
use GuzzleHttp\Client as GuzzleHttpClient;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Integration\Tizo\Dvla\VehicleEnquiry\Tool\GuzzlePsr18ClientDecorator;

final class ClientTest extends TestCase
{
    private function createFixture(): Client
    {
        $configPath = __DIR__ .'/../../config_test.json';
        Assert::that($configPath)
            ->file(\sprintf('Please copy the config_test.dist.json to the %s path.', $configPath))
            ->readable('The config_test.json file must be readable.');
        $config = \file_get_contents($configPath);
        Assert::that($config)->isJsonString();
        $config = \json_decode($config, true, 512, \JSON_THROW_ON_ERROR);

        Assert::that($config)
            ->isArray('Make sure the The config_test.json file contains valid JSON as like the config_test.dist.json.')
            ->keyExists('baseUri')
            ->keyExists('token');

        return new Client(
            new ApiKeyAuthHttpClientDecorator(
                new Psr18ClientDecorator(
                    new GuzzlePsr18ClientDecorator(
                        new GuzzleHttpClient()
                    ),
                ),
                ApiKey::fromString($config['token'])
            ),
            new Uri($config['baseUri'])
        );
    }

    #[Test]
    public function it_should_request_vehicle_details_from_the_dvla_vehicle_enquiry_api(): void
    {
        $registrationNumber = 'AA19PPP';
        $fixture = $this->createFixture();
        $request = EnquiryRequest::with(RegistrationNumber::fromString($registrationNumber));

        $response = $fixture->vehicles()->enquireDetails($request);

        $this->assertSame($registrationNumber, $response->getRegistrationNumber()->toString());
        $this->assertSame(2019, $response->getYearOfManufacture());
    }
}
