<?php

namespace Muscobytes\CoresignalDbApi\Casts\Company;

use Muscobytes\CoresignalDbApi\DTO\Company\LocationDTO;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class LocationCollectionCaster implements Caster
{
    /**
     * @throws UnknownProperties
     */
    public function cast(mixed $value): array
    {
        if (! is_array($value)) {
            throw new \Exception("Can only cast arrays to arrays of LocationDTO");
        }

        return array_map(
            fn (array $data) => new LocationDTO(...$data),
            $value
        );
    }
}
