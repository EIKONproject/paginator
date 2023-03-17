<?php

/**
 * Page classes for paginator elements
 *
 * @package EikonPaginator
 */

declare(strict_types=1);

namespace EikonPaginator\Elements;


/**
 * Post element
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class Post extends Page
{
    /**
     * Return the metadata fields of the post.
     *
     * Every post metadata inherit all the metadata of the `Page` plus:
     * - `author_shortcode`: shortcode identifying the author of the post
     *
     * @return array Names of the metadata fields of the element.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function metadata_fields(): array
    {
        $fields = parent::metadata_fields();
        $fields = array_merge($fields, array(
            "author_shortcode",
        ));
        return $fields;
    }
}



/**
 * Interview element
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class Interview extends Post
{
    /**
     * Return the metadata fields of the interview.
     *
     * Every interview metadata inherit all the metadata of the `Post` plus:
     * - `interviewee_shortcode`: shortcode identifying the interviewee
     *
     * @return array Names of the metadata fields of the element.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function metadata_fields(): array
    {
        $fields = parent::metadata_fields();
        $fields = array_merge($fields, array(
            "interviewee_shortcode",
        ));
        return $fields;
    }
}
