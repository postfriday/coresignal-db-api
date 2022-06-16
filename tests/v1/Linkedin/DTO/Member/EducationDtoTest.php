<?php

namespace Tests;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member\EducationDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class EducationDtoTest extends TestCase
{
    /** @test
     * @dataProvider testCreateEducationDtoDataProvider
     * @covers ::EducationDTO
     * @throws UnknownProperties
     */
    public function testCreateEducationDtoTest($attributes)
    {
        $dto = new EducationDTO($attributes);
        $this->assertEquals($attributes['id'], $dto->id);
        $this->assertEquals($attributes['member_id'], $dto->member_id);
        $this->assertEquals($attributes['title'], $dto->title);
        $this->assertEquals($attributes['subtitle'], $dto->subtitle);
        $this->assertEquals($attributes['date_from'], $dto->date_from);
        $this->assertEquals($attributes['date_to'], $dto->date_to);
        $this->assertEquals($attributes['activities_and_societies'], $dto->activities_and_societies);
        $this->assertEquals($attributes['description'], $dto->description);
        $this->assertEquals(Carbon::make($attributes['created'])->getTimestamp(), $dto->created->getTimestamp());
        $this->assertEquals(Carbon::make($attributes['last_updated'])->getTimestamp(), $dto->last_updated->getTimestamp());
    }


    public function testCreateEducationDtoDataProvider(): array
    {
        $list = json_decode(file_get_contents(__DIR__ . '/fixtures/education_collection.json'), true);
        return array_map(function($element){
            return [ $element ];
        }, $list);
    }
}
