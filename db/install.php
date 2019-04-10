<?php declare(strict_types=1);

function debugC($var,bool$var_dump=false){
	echo"\r\nPrint_r ::\r\n";print_r($var);if($var_dump){echo"Var_dump ::\r\n";var_dump($var);echo"\r\n";};
}
function debug($var,bool$var_dump=false){
	echo"<br><pre>Print_r ::<br>";print_r($var);echo"</pre>";
	if($var_dump){echo"<br><pre>Var_dump ::<br>";var_dump($var);echo"</pre><br>";};
}

define( 'DOC_ROOT', $_SERVER['DOCUMENT_ROOT'] );
define( 'WEB_PATH', '/superduperstucaapp/' );

set_include_path(
	get_include_path() . PATH_SEPARATOR
	. DOC_ROOT . WEB_PATH . '/class/' . PATH_SEPARATOR
	. DOC_ROOT . WEB_PATH . '/components/' . PATH_SEPARATOR
	. DOC_ROOT . WEB_PATH . '/cfg/' . PATH_SEPARATOR );
spl_autoload_extensions( '.class.php' );
spl_autoload_register();

define(
	'INI' ,
	parse_ini_file(
		(parse_ini_file( 'config.ini.php' )[ 'config' ]),
		true ,
		INI_SCANNER_TYPED
	)
);

$db = new DBConnection();
/*
$file_handle = file( './database.sql', FILE_IGNORE_NEW_LINES);

// Removing comments from sql-file
foreach ( $file_handle as $key => $line ) {
	// Strip anything from line after '--' (comment in SQL)
	$file_handle[ $key] = strstr( $line, '--', true) ?: $line;
}

// Change every query into it's own index in a table,
//  split by ';'-character
$query_array = explode( ";", implode( "", $file_handle) );
foreach ( $query_array as $sql_query ) {
	if ( !empty($sql_query) && strlen( $sql_query) > 5 ) {
		$db->query( $sql_query );
	}
}
*/
/*
 * Fill in said tables with rows.
 * Restaurant info from restaurants.json file.
 */

/** @var Restaurant[] $json */
$json = json_decode(file_get_contents( '../json/restaurants.json' ) )->restaurants;

/** @var string $sql_query For basic restaurant information */
$sql_query = "insert into restaurant (name, latitude, longitude, food, kela, address, city) 
		values (?,?,?,?,?,?,?)";

/** @var string $sql_hours Opening lunch hours for each day */
$sql_hours = "insert into openinghours (restaurant_id, day_index, lunch_open, lunch_close) values (?,?,?,?)";

/** @var string $sql_hours Opening lunch hours for each day */
$sql_siteurls = "insert into menuurls (restaurant_id, language, url, json_url) values (?,?,?,?)";

foreach ( $json as $index => $r ) {
	debug( $r );
	$index++;
	// First the basic information
    $db->query(
    	$sql_query,
	    [ $r->name , $r->location->lat , $r->location->long , $r->food , $r->kela , $r->address , $r->city]
    );

    // Opening lunch hours
    $i = 1; // For keeping track of weekday
	foreach ( $r->normalLunchHours as $time ) {
		$db->query(
			$sql_hours,
			[ $index , $i , ($time[ 0 ]) ? $time[ 0 ] : null , ($time[ 1 ]) ? $time[ 1 ] : null ]
		);
		$i++;
	}

	// Menu URLs for different languages
	foreach ( $r->menuUrl as $lang => $url ) {
		$db->query(
			$sql_siteurls,
			[ $index , $lang , $url , ($r->menuUrl_JSON->$lang ?? null) ]
		);
	}
}
