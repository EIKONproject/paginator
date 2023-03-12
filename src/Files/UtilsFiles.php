<?php

/**
 * Useful functions for file management
 *
 * @package EikonPaginator
 */

declare(strict_types=1);

namespace EikonPaginator\Files;

use Exception;

/**
 * Load a JSON file
 *
 * @param string $path         The JSON file path.
 * @param array  $check_fields Optional. An array of fields which need to
 *                             be in the JSON file. Default to an empty array.
 *
 * @return mixed Value encoded in json in appropriate PHP type.
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
function load_json(
    string $path,
    array $check_fields = array()
) {
    // Read json file
    $string = file_get_contents($path);
    if (!$string) {
        throw new Exception("No file '$path' found");
    }
    $json = json_decode($string, true);
    // Check fields
    foreach ($check_fields as $field) {
        if (!array_key_exists($field, $json)) {
            throw new Exception("No field '$field' in '$path'");
        }
    }
    // Return loaded json
    return $json;
}


/**
 * Store a JSON-like object to a JSON file
 *
 * @param mixed  $json         A JSON-like object.
 * @param string $path         The file path for the JSON file created.
 * @param array  $check_fields Optional. An array of fields which need to
 *                             be in the JSON file. Default to an empty array
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
function store_json(
    $json,
    string $path,
    array $check_fields = array()
): void {
    // Check fields
    foreach ($check_fields as $field) {
        if (!array_key_exists($field, $json)) {
            throw new Exception("No field '$field' in '$path'");
        }
    }
    // Write json file
    $json = json_encode($json);
    file_put_contents($path, $json);
}