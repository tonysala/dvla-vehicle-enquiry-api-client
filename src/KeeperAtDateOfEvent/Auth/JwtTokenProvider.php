<?php

declare(strict_types=1);

namespace Tizo\Dvla\KeeperAtDateOfEvent\Auth;

use DateTimeImmutable;
use Tizo\Dvla\KeeperAtDateOfEvent\Auth\ValueObject\JwtToken;
use Tizo\Dvla\VehicleEnquiry\Client\HttpClient;
use Tizo\Dvla\VehicleEnquiry\Client\Response;
use Tizo\Dvla\VehicleEnquiry\Client\ValueObject\HttpMethod;
use Psr\Http\Message\UriInterface;

final class JwtTokenProvider
{
    private ?JwtToken $token = null;

    private ?DateTimeImmutable $expiresAt = null;

    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly UriInterface $baseUri,
        private readonly string $username,
        private readonly string $password,
    ) {
    }

    public function token(): JwtToken
    {
        if ($this->token === null || $this->isExpired()) {
            $this->authenticate();
        }

        return $this->token;
    }

    public function refresh(): JwtToken
    {
        $this->authenticate();

        return $this->token;
    }

    private function isExpired(): bool
    {
        return $this->expiresAt === null || (new DateTimeImmutable()) >= $this->expiresAt;
    }

    private function authenticate(): void
    {
        $uri = $this->baseUri->withPath(
            $this->baseUri->getPath() . '/thirdparty-access/v1/authenticate'
        );

        $response = $this->httpClient->request(
            $uri,
            HttpMethod::POST,
            [
                'username' => $this->username,
                'password' => $this->password,
            ]
        );

        $data = $response->content()->decode();
        if (!isset($data['token']) || !isset($data['expires_in'])) {
            throw new \RuntimeException('Invalid authentication response');
        }

        $this->token = JwtToken::fromString((string) $data['token']);
        $this->expiresAt = (new DateTimeImmutable())
            ->modify('+' . (int) $data['expires_in'] . ' seconds');
    }
}
