<?php

namespace Muscobytes\CoresignalDbApi\DTO\Company;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\Casts\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class FeaturedEmployeeDTO extends DataTransferObject
{
    public int $id;

    public int $company_id;

    public string $url;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public int $deleted;
}
