<?php

namespace Muscobytes\CoresignalDbApi\v1\Linkedin\Casts;

use Carbon\Carbon;
use Spatie\DataTransferObject\Caster;

class CarbonCaster implements Caster
{
    public function cast(mixed $value): Carbon|null
    {
        return Carbon::make($value);
    }
}
