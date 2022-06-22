<?php

namespace Tests;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Muscobytes\CoresignalDbApi\DTO\Member\CourseDTO;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CourseDtoTest extends TestCase
{
    /** @test
     * @dataProvider createCourseDtoDataProvider
     * @covers ::CourseDTO
     * @throws UnknownProperties
     */
    public function createCourseDtoTest($attributes)
    {
        $dto = new CourseDTO($attributes);
        $this->assertEquals($attributes['id'], $dto->id);
        $this->assertEquals($attributes['member_id'], $dto->member_id);
        $this->assertEquals($attributes['position'], $dto->position);
        $this->assertEquals($attributes['courses'], $dto->courses);
        $this->assertEquals(Carbon::make($attributes['created'])->getTimestamp(), $dto->created->getTimestamp());
        $this->assertEquals(Carbon::make($attributes['last_updated'])->getTimestamp(), $dto->last_updated->getTimestamp());
        $this->assertEquals($attributes['deleted'], $dto->deleted);
    }


    public function createCourseDtoDataProvider(): array
    {
        $courses = json_decode(file_get_contents(__DIR__ . '/fixtures/courses.json'), true);
        return array_map(function($course){
            return [ $course ];
        }, $courses);
    }
}
