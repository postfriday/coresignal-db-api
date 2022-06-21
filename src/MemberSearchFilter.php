<?php

namespace Muscobytes\CoresignalDbApi;

use Muscobytes\CoresignalDbApi\Traits\Filter;

class MemberSearchFilter
{
    use Filter;

    protected array $filters = [];

    protected array $allowed = [
        "name",
        "title",
        "location",
        "industry",
        "summary",
        "created_at_gte",
        "created_at_lte",
        "last_updated_gte",
        "last_updated_lte",
        "country",
        "skill",
        "certification_name",
        "experience_title",
        "experience_company_name",
        "experience_company_exact_name",
        "experience_company_website_url",
        "experience_company_website_exact_url",
        "experience_company_linkedin_url",
        "experience_company_industry",
        "experience_date_from",
        "experience_date_to",
        "experience_description",
        "experience_deleted",
        "experience_company_id",
        "active_experience",
        "keyword"
    ];


    public function __construct(string $filterName = null, string $filterValue = null)
    {
        if ($filterName) {
            $this->setFilter([$filterName, $filterValue]);
        }
    }
}
