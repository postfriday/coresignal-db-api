<?php

namespace Tests;

use Muscobytes\CoresignalDbApi\v1\Linkedin\DTO\Member\MemberDTO;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class MemberDTOTest extends TestCase
{
    /** @test
     * @dataProvider provider
     * @covers ::MemberDTO
     * @throws UnknownProperties
     */
    public function create_dto($id, $name, $title, $url, $hash, $location, $industry, $summary, $connections,
                               $recommendations_count, $logo_url, $last_response_code, $created, $last_updated,
                               $outdated, $deleted, $country, $connections_count, $experience_count, $last_updated_ux,
                               $member_shorthand_name, $member_shorthand_name_hash, $canonical_url, $canonical_hash,
                               $canonical_shorthand_name, $canonical_shorthand_name_hash, $member_also_viewed_collection)
    {
        $dto = new MemberDTO([
            "id" => $id,
            "name" => $name,
            "title" => $title,
            "url" => $url,
            "hash" => $hash,
            "location" => $location,
            "industry" => $industry,
            "summary" => $summary,
            "connections" => $connections,
            "recommendations_count" => $recommendations_count,
            "logo_url" => $logo_url,
            "last_response_code" => $last_response_code,
            "created" => $created,
            "last_updated" => $last_updated,
            "outdated" => $outdated,
            "deleted" => $deleted,
            "country" => $country,
            "connections_count" => $connections_count,
            "experience_count" => $experience_count,
            "last_updated_ux" => $last_updated_ux,
            "member_shorthand_name" => $member_shorthand_name,
            "member_shorthand_name_hash" => $member_shorthand_name_hash,
            "canonical_url" => $canonical_url,
            "canonical_hash" => $canonical_hash,
            "canonical_shorthand_name" => $canonical_shorthand_name,
            "canonical_shorthand_name_hash" => $canonical_shorthand_name_hash,
            "member_also_viewed_collection" => $member_also_viewed_collection,
        ]);

        $this->assertEquals($id, $dto->id);
        $this->assertEquals($name, $dto->name);
    }


    public function provider()
    {
        return [
            [
                "id" => 57064683,
                "name" => "Matthew McRae",
                "title" => "CEO of Arlo",
                "url" => "https://www.linkedin.com/in/mbmcrae",
                "hash" => "0dd8414b1cbf5c10fd3b97c7744cdcbb",
                "location" => "Laguna Niguel, California, United States",
                "industry" => "Consumer Electronics",
                "summary" => null,
                "connections" => "500+ connections",
                "recommendations_count" => "0",
                "logo_url" => "https://media-exp1.licdn.com/dms/image/C4E03AQHhqSSe1TKUlQ/profile-displayphoto-shrink_200_200/0/1517956444129?e=1658966400&v=beta&t=Jz_FLB10AsDrgbJoQDMpUh9AOjnctntpIoxkwaQy5Bw",
                "last_response_code" => 200,
                "created" => "2016-08-03 21:03:37",
                "last_updated" => "2022-05-25 05:35:01",
                "outdated" => 0,
                "deleted" => 0,
                "country" => "United States",
                "connections_count" => 65535,
                "experience_count" => 15,
                "last_updated_ux" => 1653456901,
                "member_shorthand_name" => "mbmcrae",
                "member_shorthand_name_hash" => "4a1381c9b2ecc03e2c2227a4e968eb79",
                "canonical_url" => "https://www.linkedin.com/in/mbmcrae",
                "canonical_hash" => "0dd8414b1cbf5c10fd3b97c7744cdcbb",
                "canonical_shorthand_name" => "mbmcrae",
                "canonical_shorthand_name_hash" => "4a1381c9b2ecc03e2c2227a4e968eb79",
                "member_also_viewed_collection" => json_decode(file_get_contents(__DIR__ . '/fixtures/also_viewed.json'), true),
            ],
        ];
    }
}
