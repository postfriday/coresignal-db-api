<?php

namespace Muscobytes\CoresignalDbApi\Casts\Member;

use Muscobytes\CoresignalDbApi\DTO\Member\AwardDTO;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class AwardsCollectionCaster implements Caster
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
            fn (array $data) => new AwardDTO(...$data),
            $value
        );
    }
}
