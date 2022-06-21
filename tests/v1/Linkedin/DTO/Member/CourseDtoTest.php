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
        $this->assertEquals($attributes['name'], $dto->name);
        if ($attributes['created'] !== null) {
            $this->assertEquals(Carbon::make($attributes['created'])->getTimestamp(), $list[0]->created->getTimestamp());
        }
        $this->assertEquals(Carbon::make($attributes['last_updated'])->getTimestamp(), $list[0]->last_updated->getTimestamp());
    }


    public function createCourseDtoDataProvider(): array
    {
        $courses = json_decode(file_get_contents(__DIR__ . '/fixtures/courses.json'), true);
        return array_map(function($course){
            return [ $course ];
        }, $courses);
    }
}
