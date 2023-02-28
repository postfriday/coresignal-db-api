<?php
/**
 * Coresignal DB API Reference
 * https://api.coresignal.com/dbapi/docs#/
 */

namespace Muscobytes\CoresignalDbApi;

use Muscobytes\CoresignalDbApi\DTO\CompanyDTO;
use Muscobytes\CoresignalDbApi\DTO\JobDTO;
use Muscobytes\CoresignalDbApi\DTO\MemberDTO;
use Muscobytes\CoresignalDbApi\Exceptions\ClientException;
use Muscobytes\CoresignalDbApi\Exceptions\RetryLimitExceededException;
use Muscobytes\CoresignalDbApi\Exceptions\ServerErrorException;
use Muscobytes\CoresignalDbApi\Exceptions\ServiceUnavailableException;
use Muscobytes\CoresignalDbApi\Exceptions\UnknownException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class CoresignalDbApiProvider
{
    protected CoresignalClient $client;

    protected ?ResponseInterface $response = null;

    protected int $retries = 10;

    protected int $timeout = 10;


    /**
     * @param string $token
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        protected string $token,
        protected ?LoggerInterface $logger
    )
    {
    }


    /**
     * @throws RetryLimitExceededException
     */
    public function request(
        string $method,
        string $endpointUrl,
        array $payload = []
    ): array
    {
        $retry = 0;
        do {
            $this->response = null;
            try {
                $this->client = new CoresignalClient($this->token, $this->logger);
                $this->response = $this->client->request($method, $endpointUrl, $payload);
            } catch (
                ServerErrorException |
                ServiceUnavailableException |
                UnknownException |
                ClientException |
                ClientExceptionInterface $e
            ) {
                $this->_log("Params: " . print_r($payload, true));
                $this->_log("Error {$e->getCode()}: {$e->getMessage()}", 'error');
                $this->_log("Response Status Code: {$this->response?->getStatusCode()}", 'error');
                sleep($this->timeout);
                if ($retry >= $this->retries) {
                    throw new RetryLimitExceededException('Maximum retry limit reached.');
                }
                $retry++;
            }
        } while ($this->response?->getStatusCode() != 200);

        return json_decode($this->response->getBody()->getContents(), true);
    }


    protected function getResponseHeader($name): ?string
    {
        $value = $this->response->getHeader($name);
        return $value ? $value[0] : null;
    }


    public function getRemainingCredits(): int
    {
        return (int)$this->getResponseHeader('X-Credits-Remaining');
    }


    public function getNextPageAfter(): string
    {
        return $this->getResponseHeader('X-next-page-after');
    }


    public function getStatusCode(): string
    {
        return $this->response->getStatusCode();
    }


    public function getTotalPages(): string
    {
        return $this->getResponseHeader('x-total-pages');
    }


    public function getTotalResults(): string
    {
        return $this->getResponseHeader('x-total-results');
    }


    protected function _log(string $message, string $level = 'info'): void
    {
        $this->logger->log($level, date('Y.m.d H:i:s') . ' ' . $message);
    }


    /**
     * @param string $value
     * @return MemberDTO
     * @throws ClientException
     * @throws RetryLimitExceededException
     * @throws UnknownProperties
     */
    public function memberCollectBy(string $value): MemberDTO
    {
        return new MemberDTO($this->request('GET', '/v1/linkedin/member/collect/' . $value));
    }


    /**
     * @param string $memberId
     * @return MemberDTO
     * @throws ClientException
     * @throws RetryLimitExceededException
     * @throws UnknownProperties
     */
    public function memberCollectById(string $memberId): MemberDTO
    {
        return $this->memberCollectBy($memberId);
    }


    /**
     * @param string $shorthandName
     * @return MemberDTO
     * @throws ClientException
     * @throws RetryLimitExceededException
     * @throws UnknownProperties
     */
    public function memberCollectByShorthandName(string $shorthandName): MemberDTO
    {
        return $this->memberCollectBy($shorthandName);
    }


    /**
     * @param MemberSearchFilter $filter
     * @return array
     * @throws ClientException
     * @throws RetryLimitExceededException
     */
    public function memberSearchFilter(MemberSearchFilter $filter): array
    {
        return $this->request('POST', '/v1/linkedin/member/search/filter', $filter->getFilters());
    }


    /**
     * @param ElasticsearchQuery $query
     * @return array
     * @throws ClientException
     * @throws RetryLimitExceededException
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
     * @throws RetryLimitExceededException
     * @throws UnknownProperties
     */
    public function companyCollectBy(string $value): CompanyDTO
    {
        return new CompanyDTO($this->request('GET', '/v1/linkedin/company/collect/' . $value));
    }


    /**
     * @param string $companyId
     * @return CompanyDTO
     * @throws ClientException
     * @throws RetryLimitExceededException
     * @throws UnknownProperties
     */
    public function companyCollectById(string $companyId): CompanyDTO
    {
        return $this->companyCollectBy($companyId);
    }


    /**
     * @param string $shorthandName
     * @return CompanyDTO
     * @throws ClientException
     * @throws RetryLimitExceededException
     * @throws UnknownProperties
     */
    public function companyCollectByShorthandName(string $shorthandName): CompanyDTO
    {
        return $this->companyCollectBy($shorthandName);
    }


    /**
     * @param CompanySearchFilter $filter
     * @return array
     * @throws ClientException
     * @throws RetryLimitExceededException
     */
    public function companySearchFilter(CompanySearchFilter $filter): array
    {
        return $this->request('POST', '/v1/linkedin/company/search/filter', $filter->getFilters());
    }


    /**
     * @param ElasticsearchQuery $query
     * @return array
     * @throws ClientException
     * @throws RetryLimitExceededException
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
     * @throws RetryLimitExceededException
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
     * @throws RetryLimitExceededException
     * @throws UnknownProperties
     */
    public function jobCollectById(string $id): JobDTO
    {
        return $this->jobCollectBy($id);
    }


    /**
     * @param JobSearchFilter $filter
     * @param string|null $after
     * @return array
     * @throws ClientException
     * @throws RetryLimitExceededException
     */
    public function jobSearchFilter(JobSearchFilter $filter, string $after = null): array
    {
        $query = $after ? "?after={$after}" : '';
        return $this->request(
            'POST',
            '/v1/linkedin/job/search/filter' . $query,
            $filter->getFilters()
        );
    }


    /**
     * @param string $filterName
     * @param string $filterValue
     * @return array
     * @throws ClientException
     * @throws RetryLimitExceededException
     */
    public function companySearchFilterBy(string $filterName, string $filterValue): array
    {
        return $this->companySearchFilter(new CompanySearchFilter([$filterName => $filterValue]));
    }
}
