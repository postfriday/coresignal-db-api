<?php

namespace Muscobytes\CoresignalDbApi\DTO\Member;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\Casts\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class PublicationDTO extends DataTransferObject
{
    public int $id;

    public int $member_id;

    public ?string $title;

    public ?string $publisher;

    public ?string $date;

    public ?string $description;

    public ?string $authors;

    public ?string $url;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public int $deleted;
}
