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

    protected ResponseInterface $response;


    /**
     * @param string $token
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected string $token,
        protected LoggerInterface $logger,
    )
    {
    }


    /**
     * @throws ClientExceptionInterface
     */
    public function request(
        string $method,
        string $endpointUrl,
        array $payload = []
    ): array
    {
        do {
            try {
                $this->client = new CoresignalClient($this->token);
                $this->response = $this->client->request($method, $endpointUrl, $payload);
            } catch (\Exception $e) {
                $this->_log("Error {$e->getCode()}: {$e->getMessage()}", 'error');
                $this->_log("Params: " . print_r($payload, true));
                $this->_log("Response Status Code: {$this->response->getStatusCode()}", 'error');
            }
        } while(
            $this->response->getStatusCode() != 200
        );



        return json_decode($this->response->getBody()->getContents(), true);
    }


    protected function _log(string $message, string $level = 'info'): void
    {
        $this->logger->log($level, date('Y.m.d H:i:s') . ' ' . $message);
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


    public function getNextPageAfter(): int
    {
        return $this->getResponseHeader('X-next-page-after');
    }


    public function addHeader($key, $value): void
    {
        $this->client->addHeader($key, $value);
    }


    /**
     * @param string $value
     * @return MemberDTO
     * @throws UnknownProperties
     */
    public function memberCollectBy(string $value): MemberDTO
    {
        return new MemberDTO($this->request('GET', '/v1/linkedin/member/collect/' . $value));
    }


    /**
     * @param string $memberId
     * @return MemberDTO
     * @throws UnknownProperties
     */
    public function memberCollectById(string $memberId): MemberDTO
    {
        return $this->memberCollectBy($memberId);
    }


    /**
     * @param string $shorthandName
     * @return MemberDTO
     * @throws UnknownProperties
     */
    public function memberCollectByShorthandName(string $shorthandName): MemberDTO
    {
        return $this->memberCollectBy($shorthandName);
    }


    /**
     * @param MemberSearchFilter $filter
     * @return array
     */
    public function memberSearchFilter(MemberSearchFilter $filter): array
    {
        return $this->request('POST', '/v1/linkedin/member/search/filter', $filter->getFilters());
    }


    /**
     * @param ElasticsearchQuery $query
     * @return array
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
     */
    public function companyCollectBy(string $value): CompanyDTO
    {
        return new CompanyDTO($this->request('GET', '/v1/linkedin/company/collect/' . $value));
    }


    /**
     * @param string $companyId
     * @return CompanyDTO
     */
    public function companyCollectById(string $companyId): CompanyDTO
    {
        return $this->companyCollectBy($companyId);
    }


    /**
     * @param string $shorthandName
     * @return CompanyDTO
     */
    public function companyCollectByShorthandName(string $shorthandName): CompanyDTO
    {
        return $this->companyCollectBy($shorthandName);
    }


    /**
     * @param CompanySearchFilter $filter
     * @return array
     */
    public function companySearchFilter(CompanySearchFilter $filter): array
    {
        return $this->request('POST', '/v1/linkedin/company/search/filter', $filter->getFilters());
    }


    /**
     * @param ElasticsearchQuery $query
     * @return array
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
     */
    public function jobCollectBy(string $value): JobDTO
    {
        return new JobDTO($this->request('GET', '/v1/linkedin/job/collect/' . $value));
    }


    /**
     * @param string $id
     * @return JobDTO
     */
    public function jobCollectById(string $id): JobDTO
    {
        return $this->jobCollectBy($id);
    }


    /**
     * @param JobSearchFilter $filter
     * @return array
     */
    public function jobSearchFilter(JobSearchFilter $filter): array
    {
        return $this->request('POST', '/v1/linkedin/job/search/filter', $filter->getFilters());
    }


    /**
     * @param string $filterName
     * @param string $filterValue
     * @return array
     */
    public function companySearchFilterBy(string $filterName, string $filterValue): array
    {
        return $this->companySearchFilter(new CompanySearchFilter([$filterName => $filterValue]));
    }
}
