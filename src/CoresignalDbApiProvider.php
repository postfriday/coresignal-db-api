<?php
/**
 * Coresignal DB API Reference
 * https://api.coresignal.com/dbapi/docs#/
 */

namespace Muscobytes\CoresignalDbApi;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\Authentication\Bearer;
use Muscobytes\CoresignalDbApi\DTO\CompanyDTO;
use Muscobytes\CoresignalDbApi\DTO\MemberDTO;
use Muscobytes\CoresignalDbApi\Exceptions\ClientException;
use Muscobytes\CoresignalDbApi\Exceptions\ServerErrorException;
use Muscobytes\CoresignalDbApi\Exceptions\ServiceUnavailableException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class CoresignalDbApiProvider
{
    use LoggerAwareTrait;

    const BASE_URI = 'https://api.coresignal.com/dbapi';

    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    private Authentication $authentication;

    protected array $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];


    public function __construct(
        string $apikey,
        LoggerInterface $logger = null,
        ClientInterface $client = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    )
    {
        $this->authentication = new Bearer($apikey);
        $this->logger = $logger;
        $this->client = $client ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory();
    }


    public function request(
        string $method,
        string $endpointUrl,
        array $payload = []
    ): array
    {
        $uri = self::BASE_URI . $endpointUrl;

        $this->logger->debug('CoreSignalDbApi->request()', [
            'method' => $method,
            'uri' => $uri,
            'payload' => $payload
        ]);
        $request = $this->requestFactory->createRequest($method, $uri);
        $request = $this->authentication->authenticate($request);

        if (!empty($this->headers)) {
            foreach ($this->headers as $name => $value) {
                $request = $request->withHeader($name, $value);
            }
        }

        if (!empty($payload)) {
            $body = $this->streamFactory->createStream(json_encode($payload));
            $request = $request->withBody($body);
        }

        $response = $this->client->sendRequest($request);

        $statusCode = $response->getStatusCode();

        if ($statusCode > 500) {
            $reason = $response->getReasonPhrase();
            $this->logger->error('ServiceUnavailableException: ' . $statusCode . ' ' . $reason);
            throw new ServiceUnavailableException();
        }

        if ($statusCode === 500) {
            $reason = $response->getReasonPhrase();
            $this->logger->error('ServerErrorException: ' . $statusCode . ' ' . $reason);
            throw new ServerErrorException($reason, $statusCode);
        }

        if ($statusCode >= 400) {
            $reason = $response->getReasonPhrase();
            $this->logger->error('ClientException: ' . $statusCode . ' ' . $reason);
            throw new ClientException($reason, $statusCode);
        }

        $contents = json_decode($response->getBody()->getContents(), true);
        $this->logger->debug('Response contents:', $contents);
        return $contents;
    }


    public function memberCollectBy(string $value): MemberDTO
    {
        return new MemberDTO($this->request('GET', '/v1/linkedin/member/collect/' . $value));
    }


    public function memberCollectById(string $memberId): MemberDTO
    {
        return $this->memberCollectBy($memberId);
    }


    public function memberCollectByShorthandName(string $shorthandName): MemberDTO
    {
        return $this->memberCollectBy($shorthandName);
    }


    public function memberSearchFilter(MemberSearchFilter $filter): array
    {
        return $this->request('POST', '/v1/linkedin/member/search/filter', $filter->getFilters());
    }


    public function memberSearchEsdsl(ElasticsearchQuery $query): array
    {
        return $this->request('POST', '/v1/linkedin/member/search/es_dsl', [
            'query' => $query->toString()
        ]);
    }


    public function companyCollectBy(string $value): CompanyDTO
    {
        return new CompanyDTO($this->request('GET', '/v1/linkedin/company/collect/' . $value));
    }


    public function companyCollectById(string $companyId): CompanyDTO
    {
        return $this->companyCollectBy($companyId);
    }


    public function companyCollectByShorthandName(string $shorthandName): CompanyDTO
    {
        return $this->companyCollectBy($shorthandName);
    }


    public function companySearchFilter(CompanySearchFilter $filter): array
    {
        return $this->request('POST', '/v1/linkedin/company/search/filter', $filter->getFilters());
    }


    public function companySearchEsdsl(ElasticsearchQuery $query): array
    {
        return $this->request('POST', '/v1/linkedin/company/search/es_dsl', [
            'query' => $query->toString()
            ]);
    }


    public function jobCollectBy(string $value): array
    {
        return $this->request('GET', '/v1/linkedin/job/collect/' . $value);
    }


    public function jobCollectById(string $id): array
    {
        return $this->jobCollectBy($id);
    }

    public function jobSearchFilter(JobSearchFilter $filter): array
    {
        return $this->request('POST', '/v1/linkedin/job/search/filter', $filter->getFilters());
    }


    public function companySearchFilterBy(string $filterName, string $filterValue): array
    {
        return $this->companySearchFilter(new CompanySearchFilter([$filterName => $filterValue]));
    }
}
