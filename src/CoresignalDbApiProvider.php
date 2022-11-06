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
use Muscobytes\CoresignalDbApi\DTO\JobDTO;
use Muscobytes\CoresignalDbApi\DTO\MemberDTO;
use Muscobytes\CoresignalDbApi\Exceptions\ClientException;
use Muscobytes\CoresignalDbApi\Exceptions\ServerErrorException;
use Muscobytes\CoresignalDbApi\Exceptions\ServiceUnavailableException;
use Muscobytes\CoresignalDbApi\Exceptions\UnknownException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CoresignalDbApiProvider
{
    use LoggerAwareTrait;

    const BASE_URI = 'https://api.coresignal.com/cdapi';

    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    private Authentication $authentication;

    protected array $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',

    ];


    /**
     * @param string $token
     * @param LoggerInterface|null $logger
     * @param ClientInterface|null $client
     * @param RequestFactoryInterface|null $requestFactory
     * @param StreamFactoryInterface|null $streamFactory
     */
    public function __construct(
        string                  $token,
        ClientInterface         $client = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface  $streamFactory = null,
        LoggerInterface         $logger = null,
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
     * @return array
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function request(
        string $method,
        string $endpointUrl,
        array $payload = []
    ): array
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

        $response = $this->client->sendRequest($request);

        // Log remaining credits count
        $creditsRemainingHeaderName = 'X-Credits-Remaining';
        $creditsRemainingHeader = $response->getHeader($creditsRemainingHeaderName);
        if (!empty($creditsRemainingHeader)) {
            $this->logger?->info('Credits remaining:', [ $creditsRemainingHeaderName => $creditsRemainingHeader[0] ]);
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode > 500) {
            $reason = $response->getReasonPhrase();
            $this->logger?->error('ServiceUnavailableException: ' . $statusCode . ' ' . $reason);
            throw new ServiceUnavailableException($reason, $statusCode);
        } elseif ($statusCode === 500) {
            $reason = $response->getReasonPhrase();
            $this->logger?->error('ServerErrorException: ' . $statusCode . ' ' . $reason);
            throw new ServerErrorException($reason, $statusCode);
        } elseif ($statusCode >= 400) {
            $reason = $response->getReasonPhrase();
            $this->logger?->error('ClientException: ' . $statusCode . ' ' . $reason);
            throw new ClientException($reason, $statusCode);
        } elseif ($statusCode !== 200) {
            $reason = $response->getReasonPhrase();
            $this->logger?->error('ClientException: ' . $statusCode . ' ' . $reason);
            throw new UnknownException();
        }

        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * @param string $value
     * @return MemberDTO
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownProperties
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function memberCollectBy(string $value): MemberDTO
    {
        return new MemberDTO($this->request('GET', '/v1/linkedin/member/collect/' . $value));
    }


    /**
     * @param string $memberId
     * @return MemberDTO
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownProperties
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function memberCollectById(string $memberId): MemberDTO
    {
        return $this->memberCollectBy($memberId);
    }


    /**
     * @param string $shorthandName
     * @return MemberDTO
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownProperties
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function memberCollectByShorthandName(string $shorthandName): MemberDTO
    {
        return $this->memberCollectBy($shorthandName);
    }


    /**
     * @param MemberSearchFilter $filter
     * @return array
     * @throws ClientException
     * @throws ClientExceptionInterface
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownException
     */
    public function memberSearchFilter(MemberSearchFilter $filter): array
    {
        return $this->request('POST', '/v1/linkedin/member/search/filter', $filter->getFilters());
    }


    /**
     * @param ElasticsearchQuery $query
     * @return array
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function memberSearchEsdsl(ElasticsearchQuery $query): array
    {
        return $this->request('POST', '/v1/linkedin/member/search/es_dsl', [
            'query' => $query->toString()
        ]);
    }


    /**
     * @param string $value
     * @return CompanyDTO
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownProperties
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function companyCollectBy(string $value): CompanyDTO
    {
        return new CompanyDTO($this->request('GET', '/v1/linkedin/company/collect/' . $value));
    }


    /**
     * @param string $companyId
     * @return CompanyDTO
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownProperties
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function companyCollectById(string $companyId): CompanyDTO
    {
        return $this->companyCollectBy($companyId);
    }


    /**
     * @param string $shorthandName
     * @return CompanyDTO
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownProperties
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function companyCollectByShorthandName(string $shorthandName): CompanyDTO
    {
        return $this->companyCollectBy($shorthandName);
    }


    /**
     * @param CompanySearchFilter $filter
     * @return array
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws ClientExceptionInterface
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function companySearchFilter(CompanySearchFilter $filter): array
    {
        return $this->request('POST', '/v1/linkedin/company/search/filter', $filter->getFilters());
    }


    /**
     * @param ElasticsearchQuery $query
     * @return array
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws ClientExceptionInterface
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function companySearchEsdsl(ElasticsearchQuery $query): array
    {
        return $this->request('POST', '/v1/linkedin/company/search/es_dsl', [
            'query' => $query->toString()
            ]);
    }


    /**
     * @param string $value
     * @return JobDTO
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws ClientExceptionInterface
     * @throws UnknownException
     * @throws UnknownProperties
     */
    public function jobCollectBy(string $value): JobDTO
    {
        return new JobDTO($this->request('GET', '/v1/linkedin/job/collect/' . $value));
    }


    /**
     * @param string $id
     * @return JobDTO
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws ClientExceptionInterface
     * @throws UnknownException
     * @throws UnknownProperties
     */
    public function jobCollectById(string $id): JobDTO
    {
        return $this->jobCollectBy($id);
    }


    /**
     * @param JobSearchFilter $filter
     * @return array
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function jobSearchFilter(JobSearchFilter $filter): array
    {
        return $this->request('POST', '/v1/linkedin/job/search/filter', $filter->getFilters());
    }


    /**
     * @param string $filterName
     * @param string $filterValue
     * @return array
     * @throws ClientException
     * @throws ServerErrorException
     * @throws ServiceUnavailableException
     * @throws UnknownException
     * @throws ClientExceptionInterface
     */
    public function companySearchFilterBy(string $filterName, string $filterValue): array
    {
        return $this->companySearchFilter(new CompanySearchFilter([$filterName => $filterValue]));
    }
}
