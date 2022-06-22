<?php

namespace Muscobytes\CoresignalDbApi\DTO;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\Casts\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class AlsoViewedDTO extends DataTransferObject
{
    public int $id;

    public int $member_id;

    public string $url;

    public ?string $title;

    public ?string $location;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public int $deleted;
}
