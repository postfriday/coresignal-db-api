<?php

namespace Muscobytes\CoresignalDbApi\DTO\Job;

use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class IndustryDTO extends DataTransferObject
{
    public IndustryListDTO $job_industry_list;
}
