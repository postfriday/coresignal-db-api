<?php

namespace Muscobytes\CoresignalDbApi\v1\Linkedin\Casts\Member;

use Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member\SimilarProfileDTO;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SimilarProfilesCollectionCaster implements Caster
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
            fn (array $data) => new SimilarProfileDTO(...$data),
            $value
        );
    }

}
