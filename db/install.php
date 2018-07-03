<?php declare(strict_types=1);
error_reporting(E_ERROR);ini_set('display_errors', "1");

function debugC($var,bool$var_dump=false){
	echo"\r\nPrint_r ::\r\n";print_r($var);if($var_dump){echo"Var_dump ::\r\n";var_dump($var);echo"\r\n";};
}

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


/** @var Restaurant[] $json */
/*
$json = json_decode(file_get_contents( '../restaurants.json' ) )->restaurants;

$sql = "insert into restaurant (name, website_url, json_url, latitude, longitude, food, kela, address, city) 
		values (?,?,?,?,?,?,?,?,?)";
$sql_hours = "
    insert into openinghours (restaurant_id, day_index, lunch_open, lunch_close) 
	values (?,?,?,?)";

foreach ( $json as $r ) {
    $db->query( $sql, [$r->name, $r->menuUrl, $r->menuUrl_JSON, $r->location->lat, $r->location->long,
        $r->food, $r->kela, $r->address, $r->city] );
    $i = 1;
    foreach ( $r->normalLunchHours as $time ) {
        $db->query( $sql_hours, [$r->id+1, $i++, ($time[0])?$time[0]:null, ($time[1])?$time[1]:null] );
    }
}
