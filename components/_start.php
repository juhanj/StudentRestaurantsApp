<?php declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', "1");

/**
 * @param mixed $var
 * @param bool  $var_dump
 */
function debug($var,bool$var_dump=false){
	echo"<br><pre>Print_r ::<br>";print_r($var);echo"</pre>";
	if($var_dump){echo"<br><pre>Var_dump ::<br>";var_dump($var);echo"</pre><br>";};
}

/**
 * Prints formatted number: 1.000[,00]
 * @param mixed $number
 * @param int   $dec_count  [optional] default=2 <p> Number of decimals.
 * @return string
 */
function fNumber( $number, int $dec_count = 2 ) : string {
	return number_format( (float)$number, $dec_count, ',', '.' );
}

/**
 * Check feedback variable, and prevent resending form on page refresh or back button.
 * @return string $feedback
 */
function check_feedback_POST() {
	// Stop form resending
	if ( !empty($_POST) or !empty($_FILES) ){
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit();
	}

	// Check the feedback from Session data
	$feedback = isset($_SESSION["feedback"]) ? $_SESSION["feedback"] : "";
	unset($_SESSION["feedback"]);
	return $feedback;
}

/**
 * For easier access. This way any includes/requires and such can be written shorter,
 * and not be dependant on location.
 */
define(
	'DOC_ROOT',
	$_SERVER['DOCUMENT_ROOT']
);
define(
	'WEB_PATH',
	'/superduperstucaapp/'
);
define(
	'CURRENT_PAGE',
	basename( $_SERVER[ 'SCRIPT_NAME' ] , '.php' )
);

if ( CURRENT_PAGE != 'first_setup' and
	(  !isset($_COOKIE['food'])	or !isset($_COOKIE['kela'])
	or !isset($_COOKIE['vege'])	or !isset($_COOKIE['location'])
	or !isset($_COOKIE['lang']) ) ) {
	header( "Location: http://{$_SERVER['HTTP_HOST']}/superduperstucaapp/first_setup.php?need_cookies" );
	exit;
}

/*
 * Automatic class loading
 * Set folders for all possible folders where includes/requires might happen.
 */
set_include_path(
	get_include_path() . PATH_SEPARATOR
	. DOC_ROOT . WEB_PATH . '/class/' . PATH_SEPARATOR
	. DOC_ROOT . WEB_PATH . '/components/' . PATH_SEPARATOR
	. DOC_ROOT . WEB_PATH . '/cfg/' . PATH_SEPARATOR );
spl_autoload_extensions( '.class.php' );
spl_autoload_register();

/**
 * Named constant for INI-settings.
 * <code>
 * Array(
 *  ['Database'],
 *  ['Admin'],
 *  ['Misc'],
 *  ['Testing']
 * )
 * </code>
 */
define(
	'INI' ,
	parse_ini_file(
		(parse_ini_file( 'config.ini.php' )[ 'config' ]),
		true ,
		INI_SCANNER_TYPED
	)
);

session_start();

$food = isset($_COOKIE[ 'food' ])
	? (bool)$_COOKIE['food']
	: false;
$food = isset($_COOKIE[ 'kela' ])
	? (bool)$_COOKIE['kela']
	: false;

$location = !empty( $_COOKIE[ 'location' ] )
	? json_decode( $_COOKIE[ 'location' ] )
	: false;

$lang = !empty( $_COOKIE[ 'lang' ] )
	? json_decode( $_COOKIE[ 'lang' ] )
	: 'eng';

/*
 * Creating necessary objects
 */
$db = new DBConnection();
$lang = new Language( $db, $lang );
