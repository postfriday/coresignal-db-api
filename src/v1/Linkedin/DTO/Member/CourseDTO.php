<?php

namespace Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\v1\Linkedin\Casts\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class CourseDTO extends DataTransferObject
{
    public int $id;

    public string $name;

    public string $hash;

    public string $url;

    #[CastWith(CarbonCaster::class)]
    public ?Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;
}