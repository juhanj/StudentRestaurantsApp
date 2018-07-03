<?php declare(strict_types=1);

function debugC($var,bool$var_dump=false){
	echo"\r\nPrint_r ::\r\n";print_r($var);if($var_dump){echo"Var_dump ::\r\n";var_dump($var);echo"\r\n";};
}

require '../class/dbyhteys.class.php';

$db = new DByhteys( [], "../cfg/config.ini.php");

$languages = [ 'fin' , 'eng' ];

foreach ( $languages as $lang ) {

	$values = [];
	$lang_strings = json_decode( file_get_contents( "../json/lang/{$lang}.json" ) )->pages;

	foreach ( $lang_strings as $obj ) {
		foreach ( $obj->strings as $property => $propertyValue ) {
			$values[] = $lang;
			$values[] = $obj->page;
			$values[] = $property;
			$values[] = $propertyValue;
		}
	}

	$sql = "
		INSERT IGNORE INTO lang ( lang, txt_page, txt_type, txt )
		VALUES ( ?,?,?,? )
	";

	$kysymysmerkit = str_repeat(',(?,?,?,?)', ((count($values)/4)-1));

	$sql .= $kysymysmerkit;

	$db->query( $sql, $values );
}
