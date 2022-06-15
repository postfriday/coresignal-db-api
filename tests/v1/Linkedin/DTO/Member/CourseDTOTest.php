<?php

namespace Tests;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member\CourseDTO;

class CourseDTOTest extends TestCase
{
    /** @test
     * @dataProvider provider
     * @covers CourseDTO::arrayOf
     */
    public function array_of($id, $name, $hash, $url, $created, $last_updated)
    {
        $list = CourseDTO::arrayOf([
            [
                'id' => $id,
                'name' => $name,
                'hash' => $hash,
                'url' => $url,
                'created' => $created,
                'last_updated' => $last_updated
            ]
        ]);

        $this->assertCount(1, $list);
        $this->assertEquals($id, $list[0]->id);
        $this->assertEquals($name, $list[0]->name);
        if ($created !== null) {
            $this->assertEquals(Carbon::make($created)->getTimestamp(), $list[0]->created->getTimestamp());
        }
        $this->assertEquals(Carbon::make($last_updated)->getTimestamp(), $list[0]->last_updated->getTimestamp());
    }


    public function provider(): array
    {
        return [
            [
                "id" => 12016,
                "name" => "Accounting Foundations: Leases",
                "hash" => "2b36ec0b726255aa669a1c5b519bbb97",
                "url" => "https://www.linkedin.com/learning/accounting-foundations-leases",
                "created" => "2019-11-06 04:30:34",
                "last_updated" => "2019-11-06 04:30:34"
            ],
            [
                "id" => 640,
                "name" => "Financial Accounting Part 2",
                "hash" => "f44cbde97c8be15a5957c7ea5aad83ff",
                "url" => "https://www.linkedin.com/learning/financial-accounting-part-2",
                "created" => null,
                "last_updated" => "2019-11-04 12:24:51"
            ],
            [
                "id" => 255,
                "name" => "Corporate Financial Statement Analysis",
                "hash" => "4183c9463b2924a31e040c9e5f536156",
                "url" => "https://www.linkedin.com/learning/corporate-financial-statement-analysis",
                "created" => "2019-11-04 12:22:27",
                "last_updated" => "2019-11-04 12:22:27"
            ]
        ];
    }
}
