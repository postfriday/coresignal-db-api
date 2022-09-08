<?php

namespace Muscobytes\CoresignalDbApi\DTO\Company;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\Casts\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class AlsoViewedDTO extends DataTransferObject
{
    public int $id;

    public int $company_id;

    public string $viewed_company_url;

    public ?string $viewed_company_id;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public int $deleted;
}
