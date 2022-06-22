<?php

namespace Muscobytes\CoresignalDbApi;

use Muscobytes\CoresignalDbApi\Traits\Filter;

class CompanySearchFilter
{
    use Filter;

    protected array $filters = [];

    protected array $allowed = [
        "name",
        "website",
        "exact_website",
        "industry",
        "country",
        "created_at_gte",
        "created_at_lte",
        "last_updated_gte",
        "last_updated_lte",
        "employees_count_gte",
        "employees_count_lte"
    ];


    public function __construct(array $filters = null)
    {
        $this->setFilter($filters);
    }
}
