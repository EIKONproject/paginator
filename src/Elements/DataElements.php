<?php

/**
 * Datum classes for paginator elements
 *
 * @package EikonPaginator
 */

declare(strict_types=1);

namespace EikonPaginator\Elements;

/**
 * Place element
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class Place extends Datum
{
    /**
     * Return the metadata fields of the place.
     *
     * Every place metadata inherit all the metadata of the `Datum` plus:
     * - `city`: city in which the place is
     * - `country_alpha_2`: ISO 3166-1 alpha-2 code of the country in which the place is
     *
     * @return array Names of the metadata fields of the element.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function metadata_fields(): array
    {
        $fields = parent::metadata_fields();
        $fields = array_merge($fields, array(
            "city",
            "country_alpha_2",
        ));
        return $fields;
    }
}

/**
 * Person element
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class Person extends Datum
{
    /**
     * Return the metadata fields of the person.
     *
     * Every person metadata inherit all the metadata of the `Datum` plus:
     * - `surname`: surname of the person
     * - `img_src`: img src of the person
     * - `social_links`: social links of the person (in JSON format)
     *
     * @return array Names of the metadata fields of the element.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function metadata_fields(): array
    {
        $fields = parent::metadata_fields();
        $fields = array_merge($fields, array(
            "surname",
            "img_src",
            "social_links",
        ));
        return $fields;
    }
}

/**
 * Team member element
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class Member extends Person
{
    /**
     * Return the metadata fields of the team member.
     *
     * Every team member metadata inherit all the metadata of the `Person` plus:
     * - `roles`: roles of the member in the team
     * - `join_date`: timestamp on when the member joined the team
     *
     * @return array Names of the metadata fields of the element.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function metadata_fields(): array
    {
        $fields = parent::metadata_fields();
        $fields = array_merge($fields, array(
            "roles",
            "join_date",
        ));
        return $fields;
    }

    /**
     * Return the metadata of the element
     *
     * Every team member metadata parse metadata like Person plus:
     * - `join_date` values in the metadata are parsed as timestamps.
     *
     * @return mixed The loaded json metadata file in appropriate PHP type.
     *
     * @see load_json()
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function metadata()
    {
        $metadata = parent::metadata();
        $metadata["join_date"] = strtotime($metadata["join_date"]);
        return $metadata;
    }
}
