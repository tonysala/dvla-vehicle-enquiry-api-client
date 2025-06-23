<?php

declare(strict_types=1);

namespace Tizo\Dvla\VehicleEnquiry;

use Tizo\Dvla\VehicleEnquiry\Client\HttpClient;
use Tizo\Dvla\VehicleEnquiry\Client\Response;
use Tizo\Dvla\VehicleEnquiry\Client\ValueObject\Content;
use Tizo\Dvla\VehicleEnquiry\Client\ValueObject\HttpMethod;
use Tizo\Dvla\VehicleEnquiry\Error\RequestFailed;
use Tizo\Dvla\VehicleEnquiry\Error\RequestRejectedWithError;
use Tizo\Dvla\VehicleEnquiry\Error\RequestRejectedWithMessage;
use Tizo\Dvla\VehicleEnquiry\Error\ValueObject\Error;
use Tizo\Dvla\VehicleEnquiry\Error\ValueObject\Message;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;

final class Psr18ClientDecorator implements HttpClient
{
    private const HEADERS = [
        'Content-Type' => 'application/json; charset=utf-8',
    ];

    public function __construct(
        private readonly ClientInterface $client,
        private readonly RequestFactoryInterface $requestFactory = new Psr17Factory(),
        private readonly StreamFactoryInterface $streamFactory = new Psr17Factory(),
    ) {
    }

    public function request(UriInterface $uri, HttpMethod $method, ?array $data = null, array $headers = []): Response
    {
        $request = $this->createPsrRequest($method, $uri, $data, $headers);

        try {
            $psrResponse = $this->client->sendRequest($request);
            $statusCode = $psrResponse->getStatusCode();
            $content = (string) $psrResponse->getBody();

            $this->handleInvalidStatusCode($statusCode, $content);

            return Response::with(
                $statusCode,
                $psrResponse->getHeaders(),
                Content::fromString($content)
            );
        } catch (DvlaVehicleEnquiryFailure $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            throw RequestFailed::dueTo($exception);
        }
    }

    public function requestAsync(UriInterface $uri, HttpMethod $method, ?array $data = null, array $headers = []): PromiseInterface
    {
        return Create::promiseFor($this->request($uri, $method, $data, $headers));
    }

    private function createPsrRequest(HttpMethod $method, UriInterface $uri, ?array $data, array $headers): RequestInterface
    {
        $request = $this->requestFactory->createRequest($method->value, $uri);

        foreach (\array_merge(self::HEADERS, $headers) as $headerName => $headerValue) {
            $request = $request->withAddedHeader($headerName, $headerValue);
        }

        if ($data !== null) {
            $request = $request->withBody($this->streamFactory->createStream($this->createRequestBody($data)));
        }

        return $request;
    }

    private function handleInvalidStatusCode(int $statusCode, string $content): void
    {
        if ($statusCode >= 200 && $statusCode < 300) {
            return;
        }

        try {
            $responseData = \json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            if (isset($responseData['errors'][0])) {
                throw RequestRejectedWithError::of(Error::fromArray($responseData['errors'][0]));
            }

            if (isset($responseData['message'])) {
                throw RequestRejectedWithMessage::of(Message::fromString($responseData['message']));
            }
        } catch (\JsonException $exception) {
            throw RequestFailed::dueToInvalidJson($statusCode, $exception);
        }

        throw RequestFailed::dueToInvalidStatusCode($statusCode);
    }

    private function createRequestBody(array $data): string
    {
        return \json_encode($data, JSON_THROW_ON_ERROR);
    }
}
