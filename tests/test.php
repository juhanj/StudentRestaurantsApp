<?php declare(strict_types=1);
require $_SERVER['DOCUMENT_ROOT'] . '/superduperstucaapp/components/_start.php';
///////////////////////////////////////////////

debug( $_COOKIE, true );

debug( json_decode($_COOKIE['lang']), true );
