<?php

namespace Muscobytes\CoresignalDbApi\Tests\DTO\Member;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\DTO\Member\PublicationDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class PublicationDtoTest extends TestCase
{
    /** @test
     * @dataProvider createPublicationDtoDataProvider
     * @covers ::PublicationDTO
     * @throws UnknownProperties
     */
    public function createPublicationDtoTest($attributes)
    {
        $dto = new PublicationDTO($attributes);
        $this->assertEquals($attributes['id'], $dto->id);
        $this->assertEquals($attributes['member_id'], $dto->member_id);
        $this->assertEquals($attributes['title'], $dto->title);
        $this->assertEquals($attributes['publisher'], $dto->publisher);
        $this->assertEquals($attributes['date'], $dto->date);
        $this->assertEquals($attributes['description'], $dto->description);
        $this->assertEquals($attributes['authors'], $dto->authors);
        $this->assertEquals($attributes['url'], $dto->url);
        $this->assertEquals(Carbon::make($attributes['created'])->getTimestamp(), $dto->created->getTimestamp());
        $this->assertEquals(Carbon::make($attributes['last_updated'])->getTimestamp(), $dto->last_updated->getTimestamp());
        $this->assertEquals($attributes['deleted'], $dto->deleted);
    }


    public function createPublicationDtoDataProvider(): array
    {
        $list = json_decode(file_get_contents(__DIR__ . '/fixtures/publications_collection.json'), true);
        return array_map(function($element){
            return [ $element ];
        }, $list);
    }
}
