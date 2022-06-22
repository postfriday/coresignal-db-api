<?php

namespace Muscobytes\CoresignalDbApi\DTO;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\Casts\CarbonCaster;
use Muscobytes\CoresignalDbApi\Casts\AlsoViewedCollectionCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class CompanyDTO extends DataTransferObject
{
    public int $id;

    public string $url;

    public string $hash;

    public string $name;

    public string $website;

    public string $size;

    public string $industry;

    public string $description;

    public string $followers;

    public string $founded;

    public ?string $headquarters_city;

    public ?string $headquarters_country;

    public ?string $headquarters_state;

    public ?string $headquarters_street1;

    public ?string $headquarters_street2;

    public ?string $headquarters_zip;

    public string $logo_url;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public string $last_response_code;

    public string $type;

    public string $headquarters_new_address;

    public int $employees_count;

    public string $headquarters_country_restored;

    public string $headquarters_country_parsed;

    public string $company_shorthand_name;

    public string $company_shorthand_name_hash;

    public string $canonical_url;

    public string $canonical_hash;

    public string $canonical_shorthand_name;

    public string $canonical_shorthand_name_hash;

    public int $deleted;

    public int $last_updated_ux;

    public int $source_id;

    public array $company_affiliated_collection;

    #[CastWith(AlsoViewedCollectionCaster::class)]
    public array $company_also_viewed_collection;

    public array $company_crunchbase_info_collection;

    public array $company_featured_employees_collection;

    public array $company_featured_investors_collection;

    public array $company_funding_rounds_collection;

    public array $company_locations_collection;

    public array $company_similar_collection;

    public array $company_specialties_collection;

    public array $company_stock_info_collection;
}
