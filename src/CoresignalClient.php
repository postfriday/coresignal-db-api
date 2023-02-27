<?php

namespace Muscobytes\CoresignalDbApi;

use Http\Message\Authentication;
use Muscobytes\CoresignalDbApi\Exceptions\ClientException;
use Muscobytes\CoresignalDbApi\Exceptions\ServerErrorException;
use Muscobytes\CoresignalDbApi\Exceptions\ServiceUnavailableException;
use Muscobytes\CoresignalDbApi\Exceptions\UnknownException;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;


class CoresignalClient
{
    use LoggerAwareTrait;


    const BASE_URI = 'https://api.coresignal.com/cdapi';


    protected Authentication $authentication;

    protected ClientInterface $client;

    protected RequestFactoryInterface $requestFactory;

    protected StreamFactoryInterface $streamFactory;

    protected RequestInterface $request;

    protected ResponseInterface $response;


    protected array $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];


    /**
     * @param string $token
     * @param ClientInterface|null $client
     * @param RequestFactoryInterface|null $requestFactory
     * @param StreamFactoryInterface|null $streamFactory
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        string $token,
        LoggerInterface $logger = null,
        ClientInterface $client = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    )
    {
        $this->headers['Authorization'] = sprintf('Bearer %s', $token);
        $this->logger = $logger;
        $this->client = $client ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory();
    }


    /**
     * @param string $method
     * @param string $endpointUrl
     * @param array $payload
     * @return ResponseInterface
     * @throws ClientException
     * @throws ClientExceptionInterface
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownException
     */
    public function request(
        string $method,
        string $endpointUrl,
        array $payload = []
    ): ResponseInterface
    {
        $uri = self::BASE_URI . $endpointUrl;

        $this->logger?->info('CoreSignalDbApi->request()', [
            'method' => $method,
            'uri' => $uri
        ]);

        $this->request = $this->requestFactory->createRequest($method, $uri);
        $this->setHeaders($this->headers);

        if (!empty($payload)) {
            $body = $this->streamFactory->createStream(json_encode($payload));
            $this->request = $this->request->withBody($body);
        }

        $this->response = $this->client->sendRequest($this->request);

        $statusCode = $this->response->getStatusCode();
        if ($statusCode > 500) {
            $reason = $this->response->getReasonPhrase();
            $this->logger?->error('ServiceUnavailableException: ' . $statusCode . ' ' . $reason);
            throw new ServiceUnavailableException($reason, $statusCode);
        } elseif ($statusCode === 500) {
            $reason = $this->response->getReasonPhrase();
            $this->logger?->error('ServerErrorException: ' . $statusCode . ' ' . $reason);
            throw new ServerErrorException($reason, $statusCode);
        } elseif ($statusCode >= 400) {
            $reason = $this->response->getReasonPhrase();
            $this->logger?->error('ClientException: ' . $statusCode . ' ' . $reason);
            throw new ClientException($reason, $statusCode);
        } elseif ($statusCode !== 200) {
            $reason = $this->response->getReasonPhrase();
            $this->logger?->error('ClientException: ' . $statusCode . ' ' . $reason);
            throw new UnknownException();
        }

        return $this->response;
    }


    public function setHeader($key, $value): void
    {
        $this->request = $this->request->withHeader($key, $value);
    }


    public function setHeaders(array $headers = []): self
    {
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $this->setHeader($key, $value);
            }
        }
        return $this;
    }
}
