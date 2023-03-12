<?php

/**
 * Useful functions for JSON files management
 *
 * @package EikonPaginator
 */

declare(strict_types=1);

namespace EikonPaginator\Files;

use Exception;

/**
 * Check JSON fields
 *
 * @param mixed  $json         A JSON-like object.
 * @param array  $check_fields Optional. An array of fields which need to
 *                             be in the JSON file. Default to an empty array
 *
 * @return bool TRUE if the check succeeds.
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
function check_fields_json($json, array $check_fields = array()): bool
{
    // Check fields
    foreach ($check_fields as $field) {
        if (!array_key_exists($field, $json)) {
            throw new Exception("No field '$field' in '" . print_r($json, true) . "'");
        }
    }
    return true;
}


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
    $json = json_decode($string, true);
    // Check fields
    check_fields_json($json, $check_fields);
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
    check_fields_json($json, $check_fields);
    // Write json file
    $json = json_encode($json);
    file_put_contents($path, $json);
}
