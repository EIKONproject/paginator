<?php

/**
 * Useful functions for file management
 *
 * @package EikonPaginator
 */

declare(strict_types=1);

namespace EikonPaginator;

use Exception;

/**
 * Load a JSON file
 *
 * @param string $directory    The JSON file directory.
 * @param string $filename     The JSON file name.
 * @param array  $check_fields Optional. An array of fields which need to
 *                             be in the JSON file. Default to an empty array.
 *
 * @return mixed Value encoded in json in appropriate PHP type.
 *
 * @author Davide Lanza <davide.lanza@eikonproject.org>
 */
function load_json(
    $directory,
    $filename,
    $check_fields = array()
) {
    // Read json file
    $path = $directory . '/' . $filename;
    $string = \file_get_contents($path);
    if (!$string) {
        throw new Exception("No file '$path' found");
    }
    $json = \json_decode($string, true);
    // Check fields
    foreach ($check_fields as $field) {
        if (!array_key_exists($field, $json)) {
            throw new Exception("No field '$field' in '$path'");
        }
    }
    // Return loaded json
    return $json;
}
