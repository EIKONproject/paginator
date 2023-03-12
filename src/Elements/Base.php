<?php

/**
 * Base classes for Paginator elements
 *
 * @package Paginator
 */

declare(strict_types=1);

namespace EikonProject\Paginator;

/**
 * Base element.
 *
 * Base abstract class for all the paginator elements.
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
abstract class AbstractElement
{
    protected $directory;

    /**
     * Construct an element with an associated directory
     *
     * @param string $directory the path to the associated directory.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    /**
     * Return the metadata fields of the element.
     *
     * By default, every element metadata should contain at least:
     * - shortcode: a shortcode identifier for the element,
     * - name: the display name,
     * - description: a descriptive text of the element.
     *
     * @return array Names of the metadata fields of the element
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function metadata_fields(): array
    {
        return array(
            "shortcode",
            "name",
            "description",
        );
    }

    /**
     * Return the metadata of the element
     *
     * Every element has a `metadata.json` file in the associated directory.
     * This method loads and returns that JSON.
     *
     * @return mixed The loaded json metadata file in appropriate PHP type.
     *
     * @see load_json()
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function metadata(): mixed
    {
        return load_json($this->directory, "metadata.json", $this->metadata_fields());
    }
}

/**
 * Set element
 *
 * Can group categories of entities (Posts, Data, and their child classes).
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class Set extends AbstractElement
{
}

/**
 * Category element
 *
 * Can group entities (Posts, Data, and their child classes).
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class Category extends AbstractElement
{
}


/**
 * Abstract base entity element
 *
 * Base class for Posts, Data, and their child classes.
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
abstract class BaseEntity extends AbstractElement
{
}


/**
 * Datum element
 *
 * Represent a datum with metadata associated but no frontend page.
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class Datum extends BaseEntity
{
}


/**
 * Page element
 *
 * Represent a page with metadata associated and a frontend page available.
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class Page extends BaseEntity
{
    /**
     * Return the metadata fields of the page.
     *
     * Every page metadata inherit all the metadata of the BaseEntity plus:
     * - date: page publication date
     * - domain_url: domain URL of the website (e.g. www.domain.com)
     * - relative_url: relative URL if the page (e.g. /relative/url/to/page/)
     * - cover_img_src: the page cover image src (e.g. www.domain.com/some/image.jpg)
     *
     * @return array Names of the metadata fields of the element.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function metadata_fields(): array
    {
        $fields = parent::metadata_fields();
        $fields = array_merge($fields, array(
            "date",
            "url",
            "relative_url",
            "cover_img_src",
        ));
        return $fields;
    }
}
