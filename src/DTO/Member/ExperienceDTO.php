<?php

namespace Muscobytes\CoresignalDbApi\DTO\Member;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\Casts\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class ExperienceDTO extends DataTransferObject
{
    public int $id;

    public int $member_id;

    public ?string $title;

    public ?string $location;

    public ?int $company_id;

    public ?string $company_name;

    public ?string $company_url;

    public ?string $date_from;

    public ?string $date_to;

    public ?string $duration;

    public ?string $description;

    public ?string $order_in_profile;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public int $deleted;




}
