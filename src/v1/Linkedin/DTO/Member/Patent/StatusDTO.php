<?php

namespace Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member\Patent;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\v1\Linkedin\Casts\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class StatusDTO extends DataTransferObject
{
    public int $id;

    public string $status;

    public string $hash;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;
}
