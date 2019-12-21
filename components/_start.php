<?php declare(strict_types=1);

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
