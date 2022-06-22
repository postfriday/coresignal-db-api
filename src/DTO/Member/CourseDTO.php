<?php

namespace Muscobytes\CoresignalDbApi\DTO\Member;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\Casts\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class CourseDTO extends DataTransferObject
{
    public int $id;

    public string $member_id;

    public ?string $position;

    public string $courses;

    #[CastWith(CarbonCaster::class)]
    public ?Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public int $deleted;
}
