<!DOCTYPE html>
<html lang='en'>
<?php
require_once dirname(dirname(__FILE__)) . '/Packages/Paginator/_RequireAll.php';

use EikonPaginator\Elements\Set;
use EikonPaginator\Spiders\SetSpider;

$cache_root = dirname(__FILE__) . "/Cache/spiders/";

$pages_set_1 = new Set(dirname(__FILE__) . '/pages_set_1/');
$spider_category_1 = new SetSpider($pages_set_1, "EikonPaginator\Elements\Page", $cache_root);

?>

<head>
</head>

<body>
    <pre><?php print_r($spider_category_1->get()); ?></pre>
</body>

</html>
