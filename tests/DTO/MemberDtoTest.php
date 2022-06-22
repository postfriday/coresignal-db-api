<?php

namespace Muscobytes\CoresignalDbApi\Tests\DTO;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\DTO\AlsoViewedDTO;
use Muscobytes\CoresignalDbApi\DTO\Member\AwardDTO;
use Muscobytes\CoresignalDbApi\DTO\Member\CourseDTO;
use Muscobytes\CoresignalDbApi\DTO\Member\EducationDTO;
use Muscobytes\CoresignalDbApi\DTO\Member\ExperienceDTO;
use Muscobytes\CoresignalDbApi\DTO\Member\GroupDTO;
use Muscobytes\CoresignalDbApi\DTO\Member\PatentDTO;
use Muscobytes\CoresignalDbApi\DTO\Member\PublicationDTO;
use Muscobytes\CoresignalDbApi\DTO\Member\SimilarProfileDTO;
use Muscobytes\CoresignalDbApi\DTO\Member\WebsiteDTO;
use Muscobytes\CoresignalDbApi\DTO\MemberDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class MemberDtoTest extends TestCase
{
    /** @test
     * @dataProvider createMemberDtoDataProvider
     * @covers ::MemberDTO
     * @throws UnknownProperties
     */
    public function createMemberDtoTest($attributes)
    {
        $dto = new MemberDTO($attributes);

        $generic_properties = [
            'id',
            'name',
            'title',
            'url',
            'hash',
            'location',
            'industry',
            'summary',
            'connections',
            'recommendations_count',
            'logo_url',
            'last_response_code',
            'outdated',
            'deleted',
            'country',
            'connections_count',
            'experience_count',
            'last_updated_ux',
            'member_shorthand_name',
            'member_shorthand_name_hash',
            'canonical_url',
            'canonical_hash',
            'canonical_shorthand_name',
            'canonical_shorthand_name_hash',
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
            'member_also_viewed_collection' => AlsoViewedDTO::class,
            'member_awards_collection' => AwardDTO::class,
            'member_courses_collection' => CourseDTO::class,
            'member_education_collection' => EducationDTO::class,
            'member_experience_collection' => ExperienceDTO::class,
            'member_groups_collection' => GroupDTO::class,
            'member_patents_collection' => PatentDTO::class,
            'member_publications_collection' => PublicationDTO::class,
            'member_similar_profiles_collection' => SimilarProfileDTO::class,
            'member_websites_collection' => WebsiteDTO::class
        ];
        foreach ($collection_properties as $propertyName => $className) {
            $this->assertCount(count($attributes[$propertyName]), $dto->{$propertyName});
            foreach ($dto->{$propertyName} as $item) {
                $this->assertInstanceOf($className, $item);
            }
        }
    }


    public function createMemberDtoDataProvider()
    {
        $members = json_decode(file_get_contents(__DIR__ . '/fixtures/members.json'), true);
        return array_map(function($member){
            return [ $member ];
        }, $members);
    }
}
