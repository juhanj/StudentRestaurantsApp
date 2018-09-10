<?php declare(strict_types=1);
error_reporting(E_ERROR);ini_set('display_errors', "1");

function debugC($var,bool$var_dump=false){
	echo"\r\nPrint_r ::\r\n";print_r($var);if($var_dump){echo"Var_dump ::\r\n";var_dump($var);echo"\r\n";};
}

/*
 * Creating the tables in the database
 */
require '../class/dbyhteys.class.php';
$db = new DByhteys( [], '../cfg/config.ini.php' );

$f = file('./database.sql', FILE_IGNORE_NEW_LINES); // Tietokannan taulut
// Poistetaan .sql-tiedoston kommentit
foreach ( $f as $k => $v ) {
	$f[$k] = strstr($v, '--', true) ?: $v;
}
// Muunnetaan jokainen query omaan indexiin
$db_file = explode( ";", implode("", $f) );
foreach ( $db_file as $sql ) {
	if ( !empty($sql) && strlen($sql) > 5 ) {
		$db->query( $sql );
	}
}


/*
 * Fill in said tables with rows.
 * Restaurant info from restaurants.json file.
 */

/** @var Restaurant[] $json */
$json = json_decode(file_get_contents( '../json/restaurants-new.json' ) )->restaurants;

/** @var string $sql For basic restaurant information */
$sql = "insert into restaurant (name, json_url, latitude, longitude, food, kela, address, city) 
		values (?,?,?,?,?,?,?,?)";

/** @var string $sql_hours Opening lunch hours for each day */
$sql_hours = "insert into openinghours (restaurant_id, day_index, lunch_open, lunch_close) values (?,?,?,?)";

/** @var string $sql_hours Opening lunch hours for each day */
$sql_siteurls = "insert into menuurls (restaurant_id, language, url) values (?,?,?)";

foreach ( $json as $index => $r ) {
	$index++;
	// First the basic information
    $db->query( $sql, [$r->name, $r->menuUrl_JSON, $r->location->lat, $r->location->long,
        $r->food, $r->kela, $r->address, $r->city] );

    // Opening lunch hours
    $i = 1; // For keeping track of weekday
	foreach ( $r->normalLunchHours as $time ) {
		$db->query( $sql_hours, [$index, $i, ($time[0])?$time[0]:null, ($time[1])?$time[1]:null] );
		$i++;
	}

	// Menu URLs for different languages
	foreach ( $r->menuUrl as $lang => $url ) {
		$db->query( $sql_siteurls, [$index, $lang, $url] );
	}
}
