<?php

/**
 * Basic classes for spiders
 *
 * @package EikonPaginator
 */

declare(strict_types=1);

namespace EikonPaginator\Spiders;

use DirectoryIterator;
use EikonPaginator\Elements\BaseEntity;
use EikonPaginator\Elements\Category;
use EikonPaginator\Elements\Set;
use EikonPaginator\Files\CachedJson;

/**
 * Create a multidimensional array directory map
 *
 * It builds a directory map for a specified directory, e.g.:
 * ```php
 * Array
 * (
 *     [dir_name] => Array
 *         (
 *             [0] => file.ext
 *             [1] => file.ext
 *         )
 *
 *     [dir_name] => Array
 *         (
 *             [0] => [dir_name] => Array
 *                 (
 *                     [0] => file.ext
 *                     [1] => file.ext
 *                 )
 *             [1] => [dir_name] => Array
 *                 (
 *                     [0] => file.ext
 *                     [1] => file.ext
 *                 )
 *         )
 * )
 * ```
 *
 * @param string $dir         root directory from where to start the dirtree.
 * @param string $regex       regex for filtering filenames.
 * @param bool   $ignoreEmpty ignore empty directories.
 *
 * @see https://stackoverflow.com/questions/45382701/multidimensional-array-directory-map
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
function dirtree(
    string $dir,
    string $regex = '',
    bool $ignoreEmpty = false
) {
    $dir = new DirectoryIterator($dir);
    $dirs = array();
    $files = array();
    foreach ($dir as $node) {
        if ($node->isDir() && !$node->isDot()) {
            $tree = dirtree(
                $node->getPathname(),
                $regex,
                $ignoreEmpty
            );
            if (!$ignoreEmpty || count($tree)) {
                $dirs[$node->getFilename()] = $tree;
            }
        } elseif ($node->isFile()) {
            $name = $node->getFilename();
            if ('' == $regex || preg_match($regex, $name)) {
                $files[] = $name;
            }
        }
    }
    return $dirs + $files;
}



/**
 * Entity Set Spider.
 *
 * Spider class for all the paginator entity sets. The spider walks through
 * files from the entity set root directory building an internal representation
 * of the entities contained in it, building a **set→category→entity metadata
 * tree** of the whole set directory. The tree is stored as a
 * `CachedJson` object.
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class SetSpider
{
    protected Set $set;
    protected string $entity_class;
    protected CachedJson $cache;

    /**
     * Construct a `Spider` object
     *
     * @param Set    $set          the spider ebtity set.
     * @param string $entity_class the class of `BaseEntity`-derived classes in the set.
     * @param string $cache_dir    the cache directory.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function __construct(Set $set, string $entity_class, string $cache_dir)
    {
        $this->set = $set;
        $this->entity_class = $entity_class;
        $this->cache = new CachedJson($cache_dir, $set->metadata()["shortcode"] . ".json", $this->generate());
    }

    /**
     * Create an entity of the class specified by $this->entity_class
     *
     * @param string $directory the path to the associated directory.
     *
     * @return BaseEntity an object of $this->entity_class extending `BaseEntity`.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    private function _create_entity($directory): BaseEntity
    {
        return new $this->entity_class($directory);
    }

    /**
     * Create the set→category→entity metadata tree of the set.
     *
     * @return array the set→category→entity metadata tree of the set.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function generate(): array
    {
        // Get the set dirtree
        $set_dirtree = dirtree($this->set->directory, '', true);

        // Build the metadata spider
        $spider_array = array();

        foreach ($set_dirtree as $cat_node => $entity_tree) {
            // Ignore numeric values (they are not folder but plain files)
            if (is_numeric($cat_node)) {
                continue;
            }
            // Add current category metadata
            $cat_dir = $this->set->directory . "/" . $cat_node;
            $category = new Category($cat_dir);
            $cat_metadata = $category->metadata();
            $spider_array[$cat_metadata["shortcode"]] = $cat_metadata;

            // Add category entities as "entities"
            $spider_array[$cat_metadata["shortcode"]]["entities"] = array();

            # Add each entity metadata to the "entities" field
            foreach (array_keys($entity_tree) as $entity_node) {
                // Ignore numeric values (ignore index and metadata of the category)
                if (is_numeric($entity_node)) {
                    continue;
                }
                $entity = $this->_create_entity($category->directory . "/" . $entity_node);
                $entity_metadata = $entity->metadata();
                $spider_array[$cat_metadata["shortcode"]]["entities"][$entity_metadata["shortcode"]] = $entity_metadata;
            }
        }
        return $spider_array;
    }

    /**
     * Store the set→category→entity metadata tree of the category.
     *
     * @return array the set→category→entity metadata tree of the category.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function store(): array
    {
        $spider_array = $this->generate();
        $this->cache->store($spider_array);
        return $spider_array;
    }

    /**
     * Get the set→category→entity metadata tree of the category.
     *
     * @return array the metadata tree.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function get(): array
    {
        if ($this->cache->is_old()) {
            return $this->store();
        }
        return $this->cache->load();
    }
}
