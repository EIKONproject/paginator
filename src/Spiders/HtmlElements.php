<?php

/**
 * Basic classes for paginator elements
 *
 * @package EikonPaginator
 */

declare(strict_types=1);

namespace EikonPaginator\Spiders\Html;

function _checkbox(
    string $html_id_prefix,
    $value
) {
    // Cast value as string
    $value = print_r($value, true);
    // Make a lowercase no_space version
    $coded_value = strtolower($value);
    $coded_value = str_replace(' ', '_', $coded_value);
    // Create the html checkbox
    $html = '';
    $html .= '<div class="label-container">';
    $html .= '<input type="checkbox" value=""';
    $html .= '    name="' . $html_id_prefix . '_checkbox_' . $coded_value . '"';
    $html .= '    id="'   . $html_id_prefix . '_checkbox_' . $coded_value . '"';
    $html .= '>';
    $html .= '<label for="' . $value . '">';
    $html .= $value . '</label>';
    return $html;
}

function category_checkboxes(
    string $html_id_prefix,
    array $set_array,
    string $key
) {
    $html = "";
    $already_added = array();
    foreach (array_values($set_array) as $category_metadata) {
        $value = $category_metadata[$key];
        if (!in_array($value, $already_added)) {
            $html .= _checkbox($html_id_prefix, $value);
            array_push($already_added, $value);
        }
    }
    $html .= '</div>';
    return $html;
}

function entity_checkboxes(
    string $html_id_prefix,
    array $set_array,
    string $key
) {
    $html = "";
    $already_added = array();
    foreach (array_values($set_array) as $category_metadata) {
        foreach ($category_metadata["entities"] as $entity_metadata) {
            $value = $entity_metadata[$key];
            if (!in_array($value, $already_added)) {
                $html .= _checkbox($html_id_prefix, $value);
                array_push($already_added, $value);
            }
        }
    }
    $html .= '</div>';
    return $html;
}
