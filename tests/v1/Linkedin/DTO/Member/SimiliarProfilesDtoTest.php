<?php

namespace Tests;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member\SimilarProfileDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SimiliarProfilesDtoTest extends TestCase
{
    /** @test
     * @dataProvider createSimilarProfileDtoDataProvider
     * @covers ::SimilarDTO
     * @throws UnknownProperties
     */
    public function createSimilarProfileDtoTest($attributes)
    {
        $dto = new SimilarProfileDTO($attributes);
        $this->assertEquals($attributes['id'], $dto->id);
        $this->assertEquals($attributes['member_id'], $dto->member_id);
        $this->assertEquals($attributes['url'], $dto->url);
        $this->assertEquals(Carbon::make($attributes['created'])->getTimestamp(), $dto->created->getTimestamp());
        $this->assertEquals(Carbon::make($attributes['last_updated'])->getTimestamp(), $dto->last_updated->getTimestamp());
        $this->assertEquals($attributes['deleted'], $dto->deleted);
    }


    public function createSimilarProfileDtoDataProvider(): array
    {
        $list = json_decode(file_get_contents(__DIR__ . '/fixtures/similiar_profiles_collection.json'), true);
        return array_map(function($element){
            return [ $element ];
        }, $list);
    }
}
