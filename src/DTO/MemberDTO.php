<?php

namespace Muscobytes\CoresignalDbApi\DTO;

use Carbon\Carbon;
use Muscobytes\CoresignalDbApi\Casts\CarbonCaster;
use Muscobytes\CoresignalDbApi\Casts\Member\AlsoViewedCollectionCaster;
use Muscobytes\CoresignalDbApi\Casts\Member\AwardsCollectionCaster;
use Muscobytes\CoresignalDbApi\Casts\Member\CoursesCollectionCaster;
use Muscobytes\CoresignalDbApi\Casts\Member\EducationCollectionCaster;
use Muscobytes\CoresignalDbApi\Casts\Member\ExperienceCollectionCaster;
use Muscobytes\CoresignalDbApi\Casts\Member\GroupsCollectionCaster;
use Muscobytes\CoresignalDbApi\Casts\Member\PatentsCollectionCaster;
use Muscobytes\CoresignalDbApi\Casts\Member\PublicationsCollectionCaster;
use Muscobytes\CoresignalDbApi\Casts\Member\SimilarProfilesCollectionCaster;
use Muscobytes\CoresignalDbApi\Casts\Member\WebsitesCollectionCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class MemberDTO extends DataTransferObject
{
    /*
     * Member ID as mentioned in coresignal’s database
     */
    public int $id;

    /*
     * Full name (as input in Linkedin's public profile) of a member
     */
    public string $name;

    /*
     * Title (Headline field in Linkedin's public profile) of a member
     */
    public string $title;

    /*
     *
     */
    public string $url;

    public string $hash;

    /*
     * Member's location as input by the member on Linkedin
     */
    public string $location;

    /*
     * Member's industry as input by the member on Linkedin (not directly related to any particular work experience).
     */
    public ?string $industry;

    /*
     * Text in member’s profile summary field.
     */
    public ?string $summary;

    public string $connections;

    public string $recommendations_count;

    public string $logo_url;

    public string $last_response_code;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon $last_updated;

    public int $outdated;

    public int $deleted;

    public string $country;

    public int $connections_count;

    public int $experience_count;

    public int $last_updated_ux;

    public string $member_shorthand_name;

    public string $member_shorthand_name_hash;

    public string $canonical_url;

    public string $canonical_hash;

    public string $canonical_shorthand_name;

    public string $canonical_shorthand_name_hash;

    #[CastWith(AlsoViewedCollectionCaster::class)]
    public ?array $member_also_viewed_collection;

    #[CastWith(AwardsCollectionCaster::class)]
    public array $member_awards_collection;

    public array $member_certifications_collection;

    #[CastWith(CoursesCollectionCaster::class)]
    public array $member_courses_collection;

    public ?array $member_courses_suggestion_collection;

    #[CastWith(EducationCollectionCaster::class)]
    public array $member_education_collection;

    #[CastWith(ExperienceCollectionCaster::class)]
    public array $member_experience_collection;

    #[CastWith(GroupsCollectionCaster::class)]
    public array $member_groups_collection;

    public array $member_interests_collection;

    public array $member_languages_collection;

    public array $member_organizations_collection;

    #[CastWith(PatentsCollectionCaster::class)]
    public array $member_patents_collection;

    public array $member_posts_see_more_urls_collection;

    public array $member_projects_collection;

    #[CastWith(PublicationsCollectionCaster::class)]
    public array $member_publications_collection;

    public array $member_recommendations_collection;

    #[CastWith(SimilarProfilesCollectionCaster::class)]
    public array $member_similar_profiles_collection;

    public array $member_skills_collection;

    public array $member_test_scores_collection;

    public array $member_volunteering_cares_collection;

    public array $member_volunteering_opportunities_collection;

    public array $member_volunteering_positions_collection;

    public array $member_volunteering_supports_collection;

    #[CastWith(WebsitesCollectionCaster::class)]
    public array $member_websites_collection;
}
