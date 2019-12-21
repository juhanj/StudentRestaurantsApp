<?php declare(strict_types=1);
require $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/studentrestaurantsapp/components/_start.php';
/*/////////////////////////////////////////////////*/


$json = '
{
	"restaurants": {
		"carelia": {
			"location": {
				"lat": 62.60393,
				"long": 29.74413
			}
		}
	}
}';

$obj = json_decode( $json );

Utils::debug( $obj );

$r = $obj->restaurants->carelia->location;
$location = [ 62.60025395192303, 29.763872623443604 ];

$d = Utils::geoDistance( $r->long, $r->lat, $location[0], $location[1] );

Utils::debug( $d, true );
