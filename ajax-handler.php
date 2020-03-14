<?php declare(strict_types=1);

$request = $_GET
	?: $_POST
	?: json_decode( file_get_contents( 'php://input' ), true );

if ( empty( $request ) ) {
	header( '400 Bad Request', true, 400 );
	exit;
}

require $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/studentrestaurantsapp/components/_start.php';
/**
 * @var Language $lang
 */

$class_controller = $request['class'] . 'Controller';

/**
 * @var Controller $controller
 */
$controller = new $class_controller();

$controller->handleRequest( $request );

$result = [
	'request' => $request,
	'result' => $controller->result
];

header( "Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}" );
header( 'Access-Control-Allow-Methods: GET, POST' );
header( "Access-Control-Allow-Headers: X-Requested-With" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

/*
 * Return result in JSON format back to client.
 * JSON_UNESCAPED_UNICODE : encode multibyte characters literally
 * JSON_UNESCAPED_SLASHES : don't escape slashes /
 * JSON_NUMERIC_CHECK : encode numeric strings --> numbers
 */
echo json_encode(
	$result,
	JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
);
