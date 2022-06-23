<?php

namespace Muscobytes\CoresignalDbApi\Casts\Company;

use Muscobytes\CoresignalDbApi\DTO\Company\AlsoViewedDTO;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class AlsoViewedCollectionCaster implements Caster
{
    /**
     * @throws UnknownProperties
     */
    public function cast(mixed $value): array
    {
        if (! is_array($value)) {
            throw new \Exception("Can only cast arrays to arrays of AlsoViewedDTO");
        }

        return array_map(
            fn (array $data) => new AlsoViewedDTO(...$data),
            $value
        );
    }
}
