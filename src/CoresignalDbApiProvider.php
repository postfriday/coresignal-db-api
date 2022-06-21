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
use Muscobytes\CoresignalDbApi\Exceptions\ClientException;
use Muscobytes\CoresignalDbApi\Exceptions\ServerErrorException;
use Muscobytes\CoresignalDbApi\Exceptions\ServiceUnavailableException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class CoresignalDbApiProvider
{
    const BASE_URI = 'https://api.coresignal.com/dbapi';

    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    private Authentication $authentication;

    protected array $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    public const METHOD_GET = 'GET';

    public const METHOD_POST = 'POST';

    const ENDPOINT_MEMBER_COLLECT_BY_ID = 0;
    const ENDPOINT_MEMBER_COLLECT_BY_SHORTHAND_NAME = 1;
    const ENDPOINT_MEMBER_SEARCH_FILTER = 2;
    const ENDPOINT_MEMBER_SEARCH_ES_DSL = 3;
    const ENDPOINT_COMPANY_COLLECT_BY_ID = 4;
    const ENDPOINT_COMPANY_COLLECT_BY_SHORTHAND_NAME = 5;
    const ENDPOINT_COMPANY_SEARCH_FILTER = 6;
    const ENDPOINT_COMPANY_SEARCH_ES_DSL = 7;
    const ENDPOINT_JOB_SEARCH_FILTER = 8;
    const ENDPOINT_JOB_COLLECT_BY_ID = 9;

    protected array $endpoints = [
        0 => ['method' => self::METHOD_GET,  'uri' => '/v1/linkedin/member/collect'],
        1 => ['method' => self::METHOD_GET,  'uri' => '/v1/linkedin/member/collect'],
        2 => ['method' => self::METHOD_POST, 'uri' => '/v1/linkedin/member/search/filter'],
        3 => ['method' => self::METHOD_POST, 'uri' => '/v1/linkedin/member/search/es_dsl'],
        4 => ['method' => self::METHOD_GET,  'uri' => '/v1/linkedin/company/collect'],
        5 => ['method' => self::METHOD_GET,  'uri' => '/v1/linkedin/company/collect'],
        6 => ['method' => self::METHOD_POST, 'uri' => '/v1/linkedin/company/search/filter'],
        7 => ['method' => self::METHOD_POST, 'uri' => '/v1/linkedin/company/search/es_dsl'],
        8 => ['method' => self::METHOD_POST,  'uri' => '/v1/linkedin/job/search/filter'],
        9 => ['method' => self::METHOD_GET,  'uri' => '/v1/linkedin/job/collect']
    ];


    public function __construct(
        string $apikey,
        ClientInterface $client = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    )
    {
        $this->authentication = new Bearer($apikey);
        $this->client = $client ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory();
    }


    public function request(
        string $method,
        string $uri,
        array $payload = []
    ): ResponseInterface
    {
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
            throw new ServiceUnavailableException();
        }

        if ($statusCode === 500) {
            throw new ServerErrorException();
        }

        if ($statusCode >= 400) {
            throw new ClientException($response->getReasonPhrase(), $statusCode);
        }
        return $response;
    }


    protected function getEndpointUri(int $endpointId, int $resourceId = 0): string
    {
        if (!in_array($endpointId, array_keys($this->endpoints))) {
            throw new ClientException('Endpoint not exists', 100);
        }

        $uri = self::BASE_URI . $this->endpoints[$endpointId]['uri'];
        if ($resourceId > 0) {
            $uri .= '/' . $resourceId;
        }
        return $uri;
    }


    protected function getEndpointMethod(int $endpointId): string
    {
        if (!in_array($endpointId, array_keys($this->endpoints))) {
            throw new ClientException('Endpoint not exists', 100);
        }
        return $this->endpoints[$endpointId]['method'];
    }


    public function memberCollectById(string $id): ResponseInterface
    {
        return $this->request(
            $this->getEndpointMethod(self::ENDPOINT_MEMBER_COLLECT_BY_ID),
            $this->getEndpointUri(self::ENDPOINT_MEMBER_COLLECT_BY_ID, $id)
        );
    }


    public function memberCollectByShorthandName(string $shorthandName): ResponseInterface
    {
        return $this->request(
            $this->getEndpointMethod(self::ENDPOINT_MEMBER_COLLECT_BY_SHORTHAND_NAME),
            $this->getEndpointUri(self::ENDPOINT_MEMBER_COLLECT_BY_SHORTHAND_NAME, $shorthandName)
        );
    }


    public function memberSearchFilter(MemberSearchFilter $filter): ResponseInterface
    {
        return $this->request(
            $this->getEndpointMethod(self::ENDPOINT_MEMBER_SEARCH_FILTER),
            $this->getEndpointUri(self::ENDPOINT_MEMBER_SEARCH_FILTER),
            $filter->getFilters()
        );
    }


    public function memberSearchEsdsl(ElasticsearchQuery $query): ResponseInterface
    {
        return $this->request(
            $this->getEndpointMethod(self::ENDPOINT_MEMBER_SEARCH_FILTER),
            $this->getEndpointUri(self::ENDPOINT_MEMBER_SEARCH_FILTER),
            ['query' => $query->toString()]
        );
    }


    public function companyCollectById(string $id): ResponseInterface
    {
        return $this->request(
            $this->getEndpointMethod(self::ENDPOINT_MEMBER_COLLECT_BY_ID),
            $this->getEndpointUri(self::ENDPOINT_MEMBER_COLLECT_BY_ID, $id)
        );
    }


    public function companyCollectByShorthandName(string $shorthandName): ResponseInterface
    {
        return $this->request(
            $this->getEndpointMethod(self::ENDPOINT_COMPANY_COLLECT_BY_SHORTHAND_NAME),
            $this->getEndpointUri(self::ENDPOINT_COMPANY_COLLECT_BY_SHORTHAND_NAME, $shorthandName)
        );
    }


    public function companySearchFilter(CompanySearchFilter $filter): ResponseInterface
    {
        return $this->request(
            $this->getEndpointMethod(self::ENDPOINT_COMPANY_SEARCH_FILTER),
            $this->getEndpointUri(self::ENDPOINT_COMPANY_SEARCH_FILTER),
            $filter->getFilters()
        );
    }


    public function companySearchEsdsl(ElasticsearchQuery $query): ResponseInterface
    {
        return $this->request(
            $this->getEndpointMethod(self::ENDPOINT_COMPANY_SEARCH_ES_DSL),
            $this->getEndpointUri(self::ENDPOINT_COMPANY_SEARCH_ES_DSL),
            ['query' => $query->toString()]
        );
    }


    public function jobCollectById(string $id): ResponseInterface
    {
        return $this->request(
            $this->getEndpointMethod(self::ENDPOINT_JOB_COLLECT_BY_ID),
            $this->getEndpointUri(self::ENDPOINT_JOB_COLLECT_BY_ID, $id)
        );
    }


    public function jobSearchFilter(JobSearchFilter $filter): ResponseInterface
    {
        return $this->request(
            $this->getEndpointMethod(self::ENDPOINT_JOB_SEARCH_FILTER),
            $this->getEndpointUri(self::ENDPOINT_JOB_SEARCH_FILTER),
            $filter->getFilters()
        );
    }
}
