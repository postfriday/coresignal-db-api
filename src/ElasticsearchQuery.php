<?php

namespace Muscobytes\CoresignalDbApi;

class ElasticsearchQuery
{
    protected string $query = '';


    public function toString(): string
    {
        return $this->query;
    }
}
