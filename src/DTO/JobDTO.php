<?php

namespace Muscobytes\CoresignalDbApi\DTO;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\Casts\CarbonCaster;
use Muscobytes\CoresignalDbApi\Casts\Job\IndustryCollectionCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class JobDTO extends DataTransferObject
{
    public int $id;

    public string $title;

    public string $description;

    public ?int $company_id;

    public string $company_name;

    public string $company_url;

    public ?string $employment_type;

    public string $country;

    public ?string $time_posted;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    public int $application_active;

    public ?string $salary;

    public string $url;

    public int $linkedin_job_id;

    #[CastWith(IndustryCollectionCaster::class)]
    public array $job_industry_collection;

    public ?string $external_url;

    public int $deleted;

    public string $applicants_count;

    public string $location;

    public ?string $seniority;

    public string $hash;
}
