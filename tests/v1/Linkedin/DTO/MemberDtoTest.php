<?php

namespace Tests;

use Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\MemberDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class MemberDtoTest extends TestCase
{
    /** @test
     * @dataProvider createMemberDtoDataProvider
     * @covers ::MemberDTO
     * @throws UnknownProperties
     */
    public function createMemberDtoTest($attributes)
    {
        $dto = new MemberDTO($attributes);

        $this->assertEquals($attributes['id'], $dto->id);
        $this->assertEquals($attributes['name'], $dto->name);
    }


    public function createMemberDtoDataProvider()
    {
        $members = json_decode(file_get_contents(__DIR__ . '/fixtures/members.json'), true);
        return array_map(function($member){
            return [ $member ];
        }, $members);
    }
}
