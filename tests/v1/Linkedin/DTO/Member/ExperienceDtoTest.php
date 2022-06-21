<?php

namespace Tests;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\DTO\Member\ExperienceDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class ExperienceDtoTest extends TestCase
{
    /** @test
     * @dataProvider createExperienceDtoDataProvider
     * @covers ::ExperienceDTO
     * @throws UnknownProperties
     */
    public function createExperienceDtoTest($attributes)
    {
        $dto = new ExperienceDTO($attributes);
        $this->assertEquals($attributes['id'], $dto->id);
        $this->assertEquals($attributes['member_id'], $dto->member_id);
        $this->assertEquals($attributes['title'], $dto->title);
        $this->assertEquals($attributes['location'], $dto->location);
        $this->assertEquals($attributes['company_name'], $dto->company_name);
        $this->assertEquals($attributes['company_url'], $dto->company_url);
        $this->assertEquals($attributes['date_from'], $dto->date_from);
        $this->assertEquals($attributes['date_to'], $dto->date_to);
        $this->assertEquals($attributes['duration'], $dto->duration);
        $this->assertEquals($attributes['description'], $dto->description);
        $this->assertEquals(Carbon::make($attributes['created'])->getTimestamp(), $dto->created->getTimestamp());
        $this->assertEquals(Carbon::make($attributes['last_updated'])->getTimestamp(), $dto->last_updated->getTimestamp());
        $this->assertEquals($attributes['deleted'], $dto->deleted);
        $this->assertEquals($attributes['order_in_profile'], $dto->order_in_profile);
        $this->assertEquals($attributes['company_id'], $dto->company_id);
    }


    public function createExperienceDtoDataProvider(): array
    {
        $list = json_decode(file_get_contents(__DIR__ . '/fixtures/experience_collection.json'), true);
        return array_map(function($element){
            return [ $element ];
        }, $list);
    }
}
