<?php

namespace Muscobytes\CoresignalDbApi\Tests\DTO;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\DTO\Job\IndustryDTO;
use Muscobytes\CoresignalDbApi\DTO\JobDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class JobDtoTest extends TestCase
{
    /** @test
     * @dataProvider createJobDtoDataProvider
     * @covers ::CompanyDTO
     * @throws UnknownProperties
     */

    public function createJobDtoTest($attributes)
    {
        $dto = new JobDTO($attributes);

        $generic_properties = [
            'id',
            'title',
            'description',
            'company_id',
            'company_name',
            'company_url',
            'employment_type',
            'country',
            'time_posted',
            'application_active',
            'salary',
            'url',
            'linkedin_job_id',
            'external_url',
            'deleted',
            'applicants_count',
            'location',
            'seniority',
            'hash'
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
            'job_industry_collection' => IndustryDTO::class,
        ];
        foreach ($collection_properties as $propertyName => $className) {
            $this->assertCount(count($attributes[$propertyName]), $dto->{$propertyName});
            foreach ($dto->{$propertyName} as $item) {
                $this->assertInstanceOf($className, $item);
            }
        }
    }


    public function createJobDtoDataProvider(): array
    {
        $dir = __DIR__ . '/fixtures/jobs/';
        $files = scandir($dir);
        $members = [];
        foreach ($files as $file) {
            if (!is_dir($dir . $file)) {
                $members[] = json_decode(file_get_contents($dir . $file), true);
            }
        }
        return array_map(function($member){
            return [ $member ];
        }, $members);
    }
}
