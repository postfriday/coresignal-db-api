<?php

namespace Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\v1\Linkedin\Casts\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class WebsiteDTO extends DataTransferObject
{
    public int $id;

    public int $member_id;

    public string $website;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public int $deleted;
}
