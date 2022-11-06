<?php

namespace Muscobytes\CoresignalDbApi\DTO\Job;

use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class IndustryListDTO extends DataTransferObject
{
    public string $industry;
}
