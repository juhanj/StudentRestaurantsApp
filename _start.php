<?php declare(strict_types=1);
error_reporting(E_ERROR);
ini_set('display_errors', "1");

/**
 * For debugging.
 * @param mixed $var
 * @param bool  $var_dump
 */
function debug($var,bool$var_dump=false){
	echo"<br><pre>Print_r ::<br>";print_r($var);echo"</pre>";
	if($var_dump){echo"<br><pre>Var_dump ::<br>";var_dump($var);echo"</pre><br>";};
}

/**
 * Tulostaa numeron muodossa 1.000[,00 [€]]
 * @param mixed $number     <p> Tulostettava numero/luku/hinta
 * @param int   $dec_count  [optional] default=2 <p> Kuinka monta desimaalia. Jos nolla, ei €-merkkiä.
 * @param bool  $ilman_euro [optional] default=FALSE <p> Tulostetaanko float-arvo ilman €-merkkiä
 * @return string
 */
function format_number( $number, int $dec_count = 2, bool $ilman_euro = false ) : string {
	if ( $dec_count == 0 ) {
		return number_format( (float)$number, 0, ',', '.' );
	} else {
		return number_format( (float)$number, $dec_count, ',', '.' )
			. ( $ilman_euro ? '' : ' €' );
	}
}

if ( !isset($_COOKIE['food'])
	or !isset($_COOKIE['kela']) or !isset($_COOKIE['vege'])
	or !isset($_COOKIE['location'])	or !isset($_COOKIE['lang']) ) {
	header( 'Location: first_setup.php?need_cookies' );
	exit;
}

set_include_path(get_include_path().PATH_SEPARATOR.'./class/');
spl_autoload_extensions('.class.php');
spl_autoload_register();

session_start();

$db = new DByhteys();

var_dump( $db );
/*
 * Haetaan kieli viimeisenä, ensinnäkin koska se vaatii validin käyttäjän,
 * ja toiseksi, koska se saattaa hakea aika paljon tietokannasta.
 */
$lang = new Language(
	$db, $_COOKIE[ 'lang' ], basename( $_SERVER[ 'SCRIPT_NAME' ] , '.php' )
);

$food = (bool)$_COOKIE[ 'food' ];
$kela = (bool)$_COOKIE[ 'kela' ];

$location = !empty( $_COOKIE[ 'location' ] ) ? json_decode( $_COOKIE[ 'location' ] ) : false;
