<?php

namespace Muscobytes\CoresignalDbApi\Tests\DTO\Company;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\DTO\Company\SpecialtiesDTO;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class SpecialtiesDtoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers ::SpecialtiesDTO
     * @dataProvider createSpecialtiesDtoDataProvider
     * @param $attributes
     * @return void
     * @throws UnknownProperties
     */
    public function testCreateSpecialtiesDto($attributes): void
    {
        $dto = new SpecialtiesDTO($attributes);
        $this->assertEquals($attributes['id'], $dto->id);
        $this->assertEquals($attributes['company_id'], $dto->company_id);
        $this->assertEquals($attributes['specialty'], $dto->specialty);
        $this->assertEquals(Carbon::make($attributes['created']), $dto->created);
        $this->assertEquals(Carbon::make($attributes['last_updated'])->getTimestamp(), $dto->last_updated->getTimestamp());
        $this->assertEquals($attributes['deleted'], $dto->deleted);
    }


    public function createSpecialtiesDtoDataProvider(): array
    {
        $data = json_decode(file_get_contents(__DIR__ . '/fixtures/specialties.json'), true);
        return array_map(function($item){
            return [$item];
        },$data);
    }

}
