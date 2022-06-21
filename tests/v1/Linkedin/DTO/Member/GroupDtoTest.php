<?php

namespace Tests;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\DTO\Member\GroupDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class GroupDtoTest extends TestCase
{
    /** @test
     * @dataProvider createGroupDtoDataProvider
     * @covers ::GroupDTO
     * @throws UnknownProperties
     */
    public function createGroupDtoTest($attributes)
    {
        $dto = new GroupDTO($attributes);
        $this->assertEquals($attributes['id'], $dto->id);
        $this->assertEquals($attributes['member_id'], $dto->member_id);
        $this->assertEquals($attributes['name'], $dto->name);
        $this->assertEquals($attributes['url'], $dto->url);
        $this->assertEquals(Carbon::make($attributes['created'])->getTimestamp(), $dto->created->getTimestamp());
        $this->assertEquals(Carbon::make($attributes['last_updated'])->getTimestamp(), $dto->last_updated->getTimestamp());
        $this->assertEquals($attributes['deleted'], $dto->deleted);
    }


    public function createGroupDtoDataProvider(): array
    {
        $list = json_decode(file_get_contents(__DIR__ . '/fixtures/groups_collection.json'), true);
        return array_map(function($element){
            return [ $element ];
        }, $list);
    }
}
