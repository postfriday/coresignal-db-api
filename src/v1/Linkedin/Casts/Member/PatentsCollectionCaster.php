<?php

namespace Muscobytes\CoresignalDbApi\v1\Linkedin\Casts\Member;

use Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member\PatentDTO;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class PatentsCollectionCaster implements Caster
{
    /**
     * @throws UnknownProperties
     */
    public function cast(mixed $value): array
    {
        if (! is_array($value)) {
            throw new \Exception("Can only cast arrays to PatentDTO");
        }

        return array_map(
            fn (array $data) => new PatentDTO(...$data),
            $value
        );
    }
}
