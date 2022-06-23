<?php

namespace Muscobytes\CoresignalDbApi\Tests\DTO\Member;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\DTO\Member\AlsoViewedDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class AlsoViewedDtoTest extends TestCase
{
    /**
     * @covers ::MemberAlsoViewedDTO
     * @dataProvider createAlsoViewedDtoDataProvider
     * @throws UnknownProperties
     */
    public function testCreateAlsoViewedDto($attributes)
    {
        $dto = new AlsoViewedDTO($attributes);
        $this->assertEquals($attributes['id'], $dto->id);
        $this->assertEquals($attributes['member_id'], $dto->member_id);
        $this->assertEquals(Carbon::make($attributes['created']), $dto->created);
    }


    public function createAlsoViewedDtoDataProvider(): array
    {
        $data = json_decode(file_get_contents(__DIR__ . '/fixtures/also_viewed_collection.json'), true);
        return array_map(function($item){
            return [$item];
        },$data);
    }
}
