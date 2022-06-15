<?php

namespace Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\v1\Linkedin\Casts\CarbonCaster;
use Muscobytes\CoresignalDbApi\v1\Linkedin\Casts\MemberAlsoViewedArrayCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class MemberDTO extends DataTransferObject
{
    /*
     * Member ID as mentioned in coresignal’s database
     */
    public int $id;

    /*
     * Full name (as input in Linkedin's public profile) of a member
     */
    public string $name;

    /*
     * Title (Headline field in Linkedin's public profile) of a member
     */
    public string $title;

    /*
     *
     */
    public string $url;

    public string $hash;

    /*
     * Member's location as input by the member on Linkedin
     */
    public string $location;

    /*
     * Member's industry as input by the member on Linkedin (not directly related to any particular work experience).
     */
    public string $industry;

    /*
     * Text in member’s profile summary field.
     */
    public ?string $summary;

    public string $connections;

    public string $recommendations_count;

    public string $logo_url;

    public string $last_response_code;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public int $outdated;

    public int $deleted;

    public string $country;

    public int $connections_count;

    public int $experience_count;

    public int $last_updated_ux;

    public string $member_shorthand_name;

    public string $member_shorthand_name_hash;

    public string $canonical_url;

    public string $canonical_hash;

    public string $canonical_shorthand_name;

    public string $canonical_shorthand_name_hash;

    #[CastWith(MemberAlsoViewedArrayCaster::class)]
    public ?array $member_also_viewed_collection;

}
