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

/**
 * For easier access. This way any includes/requires and such can be written shorter,
 * and not be dependant on location.
 */
define(
	'DOC_ROOT',
	$_SERVER['CONTEXT_DOCUMENT_ROOT']
);
define(
	'WEB_PATH',
	$_SERVER['CONTEXT_PREFIX'] . '/studentrestaurantsapp'
);
define(
	'CURRENT_PAGE',
	basename( $_SERVER[ 'SCRIPT_NAME' ] , '.php' )
);

/*
 * Automatic class loading
 * Set folders for all possible folders where includes/requires might happen.
 */
set_include_path(
	get_include_path() . PATH_SEPARATOR
	. DOC_ROOT . '/studentrestaurantsapp' . '/class/' . PATH_SEPARATOR
	. DOC_ROOT . '/studentrestaurantsapp' . '/components/' . PATH_SEPARATOR
	. DOC_ROOT . '/studentrestaurantsapp' . '/cfg/' . PATH_SEPARATOR
	. DOC_ROOT . '/studentrestaurantsapp' . '/json/' . PATH_SEPARATOR );
spl_autoload_extensions( '.class.php' );
spl_autoload_register();

session_start();

/*
 * Creating necessary objects
 */
$settings = Settings::getSettings();
$lang = Language::getLanguageStrings( $settings->lang, CURRENT_PAGE );
