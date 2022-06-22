<?php

namespace Muscobytes\CoresignalDbApi\Casts;

use Muscobytes\CoresignalDbApi\DTO\AlsoViewedDTO;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class AlsoViewedCollectionCaster  implements Caster
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
            fn (array $data) => new AlsoViewedDTO(...$data),
            $value
        );
    }
}
