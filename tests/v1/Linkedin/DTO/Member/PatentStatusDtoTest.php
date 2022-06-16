<?php

namespace Tests;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member\Patent\StatusDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class PatentStatusDtoTest extends TestCase
{
    /** @test
     * @dataProvider createPatentStatusDtoDataProvider
     * @covers ::StatusDTO
     * @throws UnknownProperties
     */
    public function createSimilarProfileDtoTest($attributes)
    {
        $dto = new StatusDTO($attributes);
        $this->assertEquals($attributes['id'], $dto->id);
        $this->assertEquals($attributes['status'], $dto->status);
        $this->assertEquals($attributes['hash'], $dto->hash);
        $this->assertEquals(Carbon::make($attributes['created'])->getTimestamp(), $dto->created->getTimestamp());
        $this->assertEquals(Carbon::make($attributes['last_updated'])->getTimestamp(), $dto->last_updated->getTimestamp());
    }


    public function createPatentStatusDtoDataProvider(): array
    {
        $list = json_decode(file_get_contents(__DIR__ . '/fixtures/patent_statuses.json'), true);
        return array_map(function($element){
            return [ $element ];
        }, $list);
    }
}
