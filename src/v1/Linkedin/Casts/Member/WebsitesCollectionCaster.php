<?php

namespace Muscobytes\CoresignalDbApi\v1\Linkedin\Casts\Member;

use Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member\WebsiteDTO;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class WebsitesCollectionCaster implements Caster
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
            fn (array $data) => new WebsiteDTO(...$data),
            $value
        );
    }

}
