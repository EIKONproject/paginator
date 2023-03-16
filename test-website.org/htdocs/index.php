<!DOCTYPE html>
<html lang='en'>
<?php
require_once dirname(dirname(__FILE__)) . '/Packages/Paginator/_RequireAll.php';

use EikonPaginator\Elements\Set;
use EikonPaginator\Spiders;

// Define the spiders needed in the page
$spiders_cache = dirname(__FILE__) . "/Cache/spiders/";
$pages_set_1 = new Set(dirname(__FILE__) . '/pages_set_1/');
$spider_set_1 = new Spiders\SetSpider($pages_set_1, "EikonPaginator\Elements\Page", $spiders_cache);
?>

<head>
</head>

<body>
    <h1>Checkboxes</h1>
    <?php // Prepare spider
    $checks_spider = $spider_set_1->get();
$checks_spider = Spiders\sort_categories($checks_spider, "shortcode");
$checks_spider = Spiders\sort_entities($checks_spider, "shortcode");
?>
    <h4>Categories <code>"name"</code> checkboxes</h4>
    <?php echo Spiders\Html\category_checkboxes("first", $spider_set_1->get(), "name"); ?>
    <h4>Entities <code>"name"</code> checkboxes</h4>
    <?php echo Spiders\Html\entity_checkboxes("first", $spider_set_1->get(), "name"); ?>
    <h4>Entities <code>"date"</code> checkboxes</h4>
    <?php echo Spiders\Html\entity_checkboxes("first", $spider_set_1->get(), "date"); ?>

    <h1>Full random array</h1>
    <?php // Prepare spider for print
$full_random = $spider_set_1->get();
$full_random = Spiders\random_entities($full_random);
$full_random = Spiders\random_categories($full_random);
$full_random = Spiders\filter_categories(
    $full_random,
    "shortcode",
    array("pages_category_1", "pages_category_3")
); ?>
    <pre><?php print_r($full_random); ?></pre>

    <h1>Filtered by categories <code>"shortcode" in ("pages_category_1", "pages_category_3")</code></h1>
    <?php // Prepare spider for print
$filtered_cats = $spider_set_1->get();
$filtered_cats = Spiders\filter_categories(
    $filtered_cats,
    "shortcode",
    array("pages_category_1", "pages_category_3")
); ?>
    <pre><?php print_r($filtered_cats); ?></pre>

    <h1>Filtered by entities <code>"name" in ("Page 1", "Page 6", "Page 8")</code></h1>
    <?php // Prepare spider for print
$filtered_cats = $spider_set_1->get();
$filtered_cats = Spiders\filter_entities(
    $filtered_cats,
    "name",
    array("Page 1", "Page 6", "Page 8")
); ?>
    <pre><?php print_r($filtered_cats); ?></pre>
</body>

</html>
