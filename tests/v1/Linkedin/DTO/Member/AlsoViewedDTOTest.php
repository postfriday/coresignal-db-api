<?php

namespace Tests;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member\AlsoViewedDTO;
use PHPUnit\Framework\TestCase;

class AlsoViewedDTOTest extends TestCase
{
    /**
     * @covers ::MemberAlsoViewedDTO
     * @dataProvider MemberAlsoViewedDtoDataProvider
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function testCreateDto($id, $member_id, $url, $title, $location, $created, $last_updated, $deleted)
    {
        $dto = new AlsoViewedDTO(
            id:             $id,
            member_id:      $member_id,
            url:            $url,
            title:          $title,
            location:       $location,
            created:        $created,
            last_updated:   $last_updated,
            deleted:        $deleted
        );

        $this->assertEquals($id, $dto->id);
        $this->assertEquals($member_id, $dto->member_id);
        $this->assertEquals(Carbon::make($created), $dto->created);
    }


    public function MemberAlsoViewedDtoDataProvider(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/fixtures/also_viewed.json'), true);
    }
}
