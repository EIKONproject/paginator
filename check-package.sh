#!/bin/bash
echo "This is a simple development script to execute all the docker checks in the CI pipeline. Use carefully!"

echo ""
echo "-------------------------------------------------------------------"
echo "🛠️Install package via composer"
echo ""
docker run -tti --rm -v $(pwd):$(pwd) -w $(pwd) --user $(id -u):$(id -g) composer:latest install --dev  || exit 1

echo ""
echo "-------------------------------------------------------------------"
echo "🔎Check code with cytopia/phplint"
echo ""
docker run --rm -v $(pwd)/src:/data cytopia/phplint  || exit 1

echo ""
echo "-------------------------------------------------------------------"
echo "🔎Check code with cytopia/php-cs-fixer"
echo ""
docker run --rm -v $(pwd)/src:/data cytopia/php-cs-fixer fix --dry-run --diff .  || exit 1

echo ""
echo "-------------------------------------------------------------------"
echo "🔎Check code with PHP Mess Detector"
echo ""
docker run -tti --rm -v $(pwd):$(pwd) -w $(pwd) --user $(id -u):$(id -g) composer:latest ./vendor/bin/phpmd src ansi cleancode,codesize,design,naming,unusedcode --exclude 'tests/*,vendor/*'  || exit 1

echo ""
echo "-------------------------------------------------------------------"
echo "🪖Test with phpunit"
echo ""
XDEBUG_MODE=coverage ./vendor/bin/phpunit tests --coverage-filter src --coverage-text --coverage-html .coverage/html --coverage-clover .coverage/clover.xml  || exit 1

echo ""
echo "-------------------------------------------------------------------"
echo "📖Check documentation with phpdoc-checker"
echo ""
docker run -tti --rm -v $(pwd):$(pwd) -w $(pwd) --user $(id -u):$(id -g) composer:latest ./vendor/bin/phpdoc-checker -d src  || exit 1

echo ""
echo "-------------------------------------------------------------------"
echo "📖Generate documentation with phpdoc"
echo ""
docker run --rm -v $(pwd):$(pwd) -w $(pwd) --user $(id -u):$(id -g) phpdoc/phpdoc:3 -d src || exit 1
