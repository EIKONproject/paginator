<?php

/**
 * Basic classes for paginator elements
 *
 * @package EikonPaginator
 */

declare(strict_types=1);

namespace EikonPaginator\Spiders;

// https://stackoverflow.com/a/4102803
function _shuffle_assoc($list)
{
    if (!is_array($list)) {
        return $list;
    }

    $keys = array_keys($list);
    shuffle($keys);
    $random = array();
    foreach ($keys as $key) {
        $random[$key] = $list[$key];
    }
    return $random;
}

function random_categories(array $set_array)
{
    $set_array = _shuffle_assoc($set_array);
    return $set_array;
}

function random_entities(array $set_array)
{
    foreach ($set_array as $category => $category_metadata) {
        $entities = $category_metadata["entities"];
        $entities = _shuffle_assoc($entities);
        $set_array[$category]["entities"] = $entities;
    }
    return $set_array;
}

function _sort(array $array, string $key)
{
    usort($array, function ($val_a, $val_b) use ($key) { // Anonymous function:
        // Compare numbers or strings non-case-sensitive
        return strcmp(strtoupper($val_a[$key]), strtoupper($val_b[$key]));
    });
    return $array;
}

function _reverse_sort(array $array, string $key)
{
    usort($array, function ($val_a, $val_b) use ($key) {
        // Switch b with a w.r.t. _sort function:
        return strcmp(strtoupper($val_b[$key]), strtoupper($val_a[$key]));
    });
    return $array;
}

function sort_categories(array $set_array, string $key)
{
    return _sort($set_array, $key);
}

function sort_rev_categories(array $set_array, string $key)
{
    return _reverse_sort($set_array, $key);
}

function sort_entities(array $set_array, string $key)
{
    foreach ($set_array as $category => $category_metadata) {
        $set_array[$category]["entities"] = _sort($category_metadata["entities"], $key);
    }
    return $set_array;
}

function sort_rev_entities(array $set_array, string $key)
{
    foreach ($set_array as $category => $category_metadata) {
        $set_array[$category]["entities"] = _reverse_sort($category_metadata["entities"], $key);
    }
    return $set_array;
}

function filter_categories(array $set_array, $metadata_key, array $values)
{
    $output = array();
    foreach ($set_array as $category => $category_metadata) {
        if (in_array($category_metadata[$metadata_key], $values)) {
            $output[$category] = $category_metadata;
        }
    }
    return $output;
}

function filter_entities(array $set_array, $metadata_key, array $values)
{
    $output = array();
    foreach ($set_array as $category => $category_metadata) {
        $output_entities = array();
        foreach ($category_metadata["entities"] as $entity) {
            if (in_array($entity[$metadata_key], $values)) {
                array_push($output_entities, $entity);
            }
        }
        if (sizeof($output_entities) > 0) {
            $output[$category]["entities"] = $output_entities;
        }
    }
    return $output;
}
