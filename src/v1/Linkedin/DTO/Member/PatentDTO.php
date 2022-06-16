<?php

namespace Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\v1\Linkedin\Casts\CarbonCaster;
use Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member\Patent\StatusDTO;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class PatentDTO extends DataTransferObject
{
    public int $id;

    public int $member_id;

    public string $title;

    public int $status_id;

    public ?string $inventors;

    public ?string $date;

    public string $url;

    public ?string $description;

    public ?string $valid_area;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public int $deleted;

    public StatusDTO $member_patent_status_list;
}
