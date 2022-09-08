<?php

namespace Muscobytes\CoresignalDbApi\Tests\DTO\Member;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\DTO\Member\PatentDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class PatentDtoTest extends TestCase
{
    /** @test
     * @dataProvider createPatentDtoDataProvider
     * @covers ::PatentDTO
     * @throws UnknownProperties
     */
    public function createSimilarProfileDtoTest($attributes)
    {
        $dto = new PatentDTO($attributes);
        $this->assertEquals($attributes['id'], $dto->id);
        $this->assertEquals($attributes['member_id'], $dto->member_id);
        $this->assertEquals($attributes['title'], $dto->title);
        $this->assertEquals($attributes['status_id'], $dto->status_id);
        $this->assertEquals($attributes['inventors'], $dto->inventors);
        $this->assertEquals($attributes['date'], $dto->date);
        $this->assertEquals($attributes['url'], $dto->url);
        $this->assertEquals($attributes['description'], $dto->description);
        $this->assertEquals($attributes['valid_area'], $dto->valid_area);
        $this->assertEquals(Carbon::make($attributes['created'])->getTimestamp(), $dto->created->getTimestamp());
        $this->assertEquals(Carbon::make($attributes['last_updated'])->getTimestamp(), $dto->last_updated->getTimestamp());
        $this->assertEquals($attributes['deleted'], $dto->deleted);
    }


    public function createPatentDtoDataProvider(): array
    {
        $list = json_decode(file_get_contents(__DIR__ . '/fixtures/patents_collection.json'), true);
        return array_map(function($element){
            return [ $element ];
        }, $list);
    }
}
