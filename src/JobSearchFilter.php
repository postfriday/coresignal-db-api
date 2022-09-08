<?php

namespace Muscobytes\CoresignalDbApi;

use Muscobytes\CoresignalDbApi\Traits\Filter;

class JobSearchFilter
{
    use Filter;

    protected array $filters = [];

    protected array $allowed = [
        "created_at_gte",
        "created_at_lte",
        "last_updated_gte",
        "last_updated_lte",
        "title",
        "keyword_description",
        "employment_type",
        "location",
        "company_id",
        "company_name",
        "company_domain",
        "company_exact_website",
        "company_linkedin_url",
        "deleted",
        "application_active",
        "country",
        "industry"
    ];


    public function __construct(string $filterName = null, string $filterValue = null)
    {
        if ($filterName) {
            $this->setFilter([$filterName, $filterValue]);
        }
    }
}
