<?php

/**
 * Classes for caching
 *
 * @package EikonPaginator
 */

declare(strict_types=1);

namespace EikonPaginator\Files;

/**
 * Class to manage cached text-based files.
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class CachedFile
{
    public static string $directory;
    public static string $filename;
    protected int $cache_days_ttl = 1; // TTL in days

    /**
     * Initialized a CachedFile object with an associated file location
     *
     * @param string $directory    the directory containing the cached file.
     * @param string $filename     the name of the cached file.
     * @param mixed  $init_content initial content of the cached file.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function __construct(string $directory, string $filename, $init_content)
    {
        $this->directory = $directory;
        $this->filename = $filename;
        // If the file does not already exist, create a blank one
        if (!file_exists($this->directory)) {
            mkdir($this->directory, 0777, true);
        }
        if (!file_exists($this->get_path())) {
            $this->store($init_content);
        }
        // Try to store and load the file to check that everything is fine
        $this->load();
    }

    /**
     * Get the cached file path
     *
     * @return string the cached file path.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function get_path(): string
    {
        return $this->directory . "/" . $this->filename;
    }

    /**
     * Load the cached file as text string
     *
     * @return string the cached file as text string.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function load()
    {
        return file_get_contents($this->get_path());
    }

    /**
     * Write/Overwrite the cached file with a new text string.
     *
     * @param string $content the text string to cache.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function store($content)
    {
        file_put_contents($this->get_path(), $content);
    }

    /**
     * Get cache age in days (how days old is the cache file)
     *
     * @return int the cache age in days.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function get_age(): int
    {
        $filename = $this->get_path();
        $last_modifies = filemtime($filename);
        $seconds_diff = time() - $last_modifies;
        $days_diff = intval(round($seconds_diff / 86400));
        return $days_diff;
    }

    /**
     * Return TRUE if the cache is old
     *
     * Return TRUE if the cache age in days is more than the cache TTL
     * specified for the class.
     *
     * @return bool TRUE if the cache is old.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function is_old(): bool
    {
        return $this->get_age() >= $this->cache_days_ttl;
    }
}


/**
 * Class to manage cached JSON-based files.
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
class CachedJson extends CachedFile
{
    /**
     * Load the cached JSON file as JSON-like object
     *
     * @return mixed the cached JSON file as JSON-like object.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function load()
    {
        return load_json($this->get_path());
    }

    /**
     * Write/Overwrite the cached file with a new JSON.
     *
     * @param mixed $content the JSON-like object to cache.
     *
     * @author Davide Lanza <davide.lanza@eikonproject.org>
     */
    public function store($content)
    {
        store_json($content, $this->get_path());
    }
}
