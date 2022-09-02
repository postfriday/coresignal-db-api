<?php

namespace Muscobytes\CoresignalDbApi;

class ElasticsearchQuery
{
    public function __construct(public array $query)
    {
        //
    }

    public function toString(): array
    {
        return $this->query;
    }
}
