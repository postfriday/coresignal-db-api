<?php

namespace Muscobytes\CoresignalDbApi\Casts\Job;

use Muscobytes\CoresignalDbApi\DTO\Job\IndustryDTO;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class IndustryCollectionCaster implements Caster
{
    /**
     * @throws UnknownProperties
     */
    public function cast(mixed $value): array
    {
        if (!is_array($value)) {
            throw new \Exception("Can only cast arrays to arrays of IndustryDTO");
        }

        return array_map(
            fn(array $data) => new IndustryDTO(...$data),
            $value
        );
    }

}
