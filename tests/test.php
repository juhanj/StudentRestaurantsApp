<?php declare(strict_types=1);
require $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/studentrestaurantsapp/components/_start.php';
/*/////////////////////////////////////////////////*/

//debug( $_SERVER );

debug( DOC_ROOT );

$testFile = DOC_ROOT . '/studentrestaurantsapp/json/menus/carelia-fi.json';

debug( $testFile );

if ( file_exists( $testFile ) ) {
	echo "hi";
}

$testFile = "/home/tko/juhanj/web-docs/studentrestaurantsapp/json/menus/carelia-fi.json";

debug( $testFile );

if ( file_exists( $testFile ) ) {
	echo "hi";
}