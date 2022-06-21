<?php

namespace Muscobytes\CoresignalDbApi\DTO\Member;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\Casts\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class EducationDTO extends DataTransferObject
{
    public int $id;

    public int $member_id;

    public string $title;

    public string $subtitle;

    public string $date_from;

    public string $date_to;

    public ?string $activities_and_societies;

    public ?string $description;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public int $deleted;

    public ?string $school_url;
}
