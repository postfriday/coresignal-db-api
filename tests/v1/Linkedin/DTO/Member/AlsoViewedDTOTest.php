<?php

namespace Tests;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\DTO\Member\AlsoViewedDTO;
use PHPUnit\Framework\TestCase;

class AlsoViewedDTOTest extends TestCase
{
    /**
     * @covers ::MemberAlsoViewedDTO
     * @dataProvider MemberAlsoViewedDtoDataProvider
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function testCreateDto($params)
    {
        $dto = new AlsoViewedDTO($params);

        $this->assertEquals($params['id'], $dto->id);
        $this->assertEquals($params['member_id'], $dto->member_id);
        $this->assertEquals(Carbon::make($params['created']), $dto->created);
    }


    public function MemberAlsoViewedDtoDataProvider(): array
    {
        $data = json_decode(file_get_contents(__DIR__ . '/fixtures/also_viewed_collection.json'), true);
        return array_map(function($item){
            return [$item];
        },$data);
    }
}
