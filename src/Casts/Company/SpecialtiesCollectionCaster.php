<?php

namespace Muscobytes\CoresignalDbApi\Casts\Company;

use Muscobytes\CoresignalDbApi\DTO\Company\SpecialtiesDTO;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SpecialtiesCollectionCaster implements Caster
{
    /**
     * @throws UnknownProperties
     */
    public function cast(mixed $value): array
    {
        if (! is_array($value)) {
            throw new \Exception("Can only cast arrays to Foo");
        }

        return array_map(
            fn (array $data) => new SpecialtiesDTO(...$data),
            $value
        );
    }
}
