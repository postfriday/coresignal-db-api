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

    protected ?LoggerInterface $logger;

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
        ClientInterface $client = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null,
        LoggerInterface $logger = null,
    )
    {
        $this->headers['Authorization'] = sprintf('Bearer %s', $token);
        $this->client = $client ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory();
        $this->logger = $logger;
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
        $request = $this->requestFactory->createRequest($method, $uri);

        if (!empty($this->headers)) {
            foreach ($this->headers as $name => $value) {
                $request = $request->withHeader($name, $value);
            }
        }

        if (!empty($payload)) {
            $body = $this->streamFactory->createStream(json_encode($payload));
            $request = $request->withBody($body);
        }

        $this->response = $this->client->sendRequest($request);

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

        return json_decode($this->response->getBody()->getContents(), true);
    }


    public function addHeader($key, $value): void
    {
        $this->headers[$key] = $value;
    }
}
