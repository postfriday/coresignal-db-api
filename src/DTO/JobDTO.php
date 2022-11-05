<?php

namespace Muscobytes\CoresignalDbApi\DTO;

use Carbon\Carbon;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class JobDTO extends DataTransferObject
{
    public int $id;

    public string $title;

    public string $country;

    public Carbon $last_updated;

    public string $employment_type;

    public int $company_id;

    public Carbon $created;

    public string $description;

    public string $time_posted;

    public int $application_active;

    public ?string $salary;

    public string $url;

    public int $linkedin_job_id;

    public array $job_industry_collection;

    public ?string $external_url;

    public int $deleted;

    public string $applicants_count;

    public string $company_name;

    public string $location;

    public string $company_url;

    public string $seniority;

    public string $hash;
}
