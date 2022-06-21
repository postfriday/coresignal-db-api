<?php

namespace Tests;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\DTO\Member\AwardDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class AwardDtoTest extends TestCase
{
    /** @test
     * @dataProvider createCourseDtoDataProvider
     * @covers ::AwardDTO
     * @throws UnknownProperties
     */
    public function createCourseDtoTest($attributes)
    {
        $dto = new AwardDTO($attributes);

        $this->assertEquals($attributes['title'], $dto->title);
        $this->assertEquals($attributes['issuer'], $dto->issuer);
        if ($attributes['created'] !== null) {
            $this->assertEquals(Carbon::make($attributes['created'])->getTimestamp(), $dto->created->getTimestamp());
        }
        $this->assertEquals(Carbon::make($attributes['last_updated'])->getTimestamp(), $dto->last_updated->getTimestamp());
    }


    public function createCourseDtoDataProvider(): array
    {
        $list = json_decode(file_get_contents(__DIR__ . '/fixtures/member_awards_collection.json'), true);
        return array_map(function($element){
            return [ $element ];
        }, $list);
    }
}
