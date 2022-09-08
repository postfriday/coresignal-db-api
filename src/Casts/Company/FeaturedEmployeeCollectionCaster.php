<?php

namespace Muscobytes\CoresignalDbApi\Casts\Company;

use Muscobytes\CoresignalDbApi\DTO\Company\FeaturedEmployeeDTO;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class FeaturedEmployeeCollectionCaster implements Caster
{
    /**
     * @throws UnknownProperties
     */
    public function cast(mixed $value): array
    {
        if (! is_array($value)) {
            throw new \Exception("Can only cast arrays to arrays of FeaturedEmployeeDTO");
        }

        return array_map(
            fn (array $data) => new FeaturedEmployeeDTO(...$data),
            $value
        );
    }
}
