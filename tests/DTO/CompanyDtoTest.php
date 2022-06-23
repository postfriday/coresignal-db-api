<?php

namespace Muscobytes\CoresignalDbApi\Tests\DTO;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\DTO\Company\FeaturedEmployeeDTO;
use Muscobytes\CoresignalDbApi\DTO\Company\LocationDTO;
use Muscobytes\CoresignalDbApi\DTO\Company\SimilarDTO;
use Muscobytes\CoresignalDbApi\DTO\Company\SpecialtiesDTO;
use Muscobytes\CoresignalDbApi\DTO\CompanyDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Muscobytes\CoresignalDbApi\DTO\Company\AlsoViewedDTO;

class CompanyDtoTest extends TestCase
{
    /** @test
     * @dataProvider createCompanyDtoDataProvider
     * @covers ::CompanyDTO
     * @throws UnknownProperties
     */
    public function createCompanyDtoTest($attributes)
    {
        $dto = new CompanyDTO($attributes);

        $generic_properties = [
            'id',
            'url',
            'hash',
            'name',
            'website',
            'size',
            'industry',
            'description',
            'followers',
            'founded',
            'headquarters_city',
            'headquarters_country',
            'headquarters_state',
            'headquarters_street1',
            'headquarters_street2',
            'headquarters_zip',
            'logo_url',
            'last_response_code',
            'type',
            'headquarters_new_address',
            'employees_count',
            'headquarters_country_restored',
            'headquarters_country_parsed',
            'company_shorthand_name',
            'company_shorthand_name_hash',
            'canonical_url',
            'canonical_hash',
            'canonical_shorthand_name',
            'canonical_shorthand_name_hash',
            'deleted',
            'last_updated_ux',
            'source_id',
        ];
        foreach ($generic_properties as $propertyName) {
            $this->assertEquals($attributes[$propertyName], $dto->{$propertyName});
        }

        /**
         * Test Carbon properties
         */
        $carbon_properties = [
            'created',
            'last_updated'
        ];
        foreach ($carbon_properties as $propertyName) {
            $this->assertEquals(Carbon::make($attributes[$propertyName])->getTimestamp(), $dto->{$propertyName}->getTimestamp());
        }

        /**
         * Test collection properties
         */
        $collection_properties = [
//            'company_affiliated_collection' => AffiliatedDTO::class,
            'company_also_viewed_collection' => AlsoViewedDTO::class,
//            'company_crunchbase_info_collection' =>
            'company_featured_employees_collection' => FeaturedEmployeeDTO::class,
//            'company_featured_investors_collection' => ,
//            'company_funding_rounds_collection' => ,
            'company_locations_collection' => LocationDTO::class,
            'company_similar_collection' => SimilarDTO::class,
            'company_specialties_collection' => SpecialtiesDTO::class,
//            'company_stock_info_collection' =>
        ];
        foreach ($collection_properties as $propertyName => $className) {
            $this->assertCount(count($attributes[$propertyName]), $dto->{$propertyName});
            foreach ($dto->{$propertyName} as $item) {
                $this->assertInstanceOf($className, $item);
            }
        }
    }


    public function createCompanyDtoDataProvider()
    {
        $companies = json_decode(file_get_contents(__DIR__ . '/fixtures/companies.json'), true);
        return array_map(function($company){
            return [ $company ];
        }, $companies);
    }
}
