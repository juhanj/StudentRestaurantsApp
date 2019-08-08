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
function check_feedback_POST() : string {
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

define(
	'INI' ,
	parse_ini_file(
		'./cfg/config.ini',
		true ,
		INI_SCANNER_TYPED
	)
);

/**
 * For easier access. This way any includes/requires and such can be written shorter,
 * and not be dependant on location.
 */
define(
	'SERVER_PATH',
	INI['server_path']
);
define(
	'WEB_PATH',
	INI['web_path']
);
define(
	'CURRENT_PAGE',
	basename( $_SERVER[ 'SCRIPT_NAME' ] , '.php' )
);

/*
 * Automatic class loading
 * Set folders for all possible folders where includes/requires might happen.
 * Relative paths due to server situation.
 */
set_include_path(
	get_include_path() . PATH_SEPARATOR
	. './components/' . PATH_SEPARATOR
	. './class/' . PATH_SEPARATOR
	. './json/' . PATH_SEPARATOR );
spl_autoload_extensions( '.class.php' );
spl_autoload_register();

session_start();

/*
 * If user is missing any cookies used on the site, send them to settings page.
 * Unless they are already there, in which case the problem is solved.
 * Unless the user is in the `test/`-dir, in which case I'm probably testing something,
 * and this was annoying me.
 */
if ( (CURRENT_PAGE != 'settings') and
	(  !isset($_COOKIE['food'])	or !isset($_COOKIE['kela'])
		or !isset($_COOKIE['lang'])	or !isset($_COOKIE['location']) ) ) {

	$_SESSION['feedback'] = "<p class='info'>First time user detected. Please see options.
		<br>Site uses browser cookies to save these options.</p>";
	header( "Location: ".WEB_PATH."/settings.php" );
	exit;
}

/*
 * Creating necessary objects
 */
$settings = new Settings( $_COOKIE );
$lang = new Language( $settings->lang );
