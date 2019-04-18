<?php declare(strict_types=1);
require __DIR__ . '/components/_start.php';

/**
 * @param $r \Restaurant
 * @param $url string
 * @param $lang string
 */
function createNewJsonMenu ( $r, $url, $lang ) {
	// Fetch weekly menu as JSON from Fazer servers:
	$json_string = file_get_contents( $url );
	// Save the original, for testing purposes
	file_put_contents("./json/menus/menu-orig-{$r->id}-{$lang}.json", $json_string );

	// Formatting the JSON a bit to be more useful and easy to use for me.
	$menu_json = new JSONMenuFormatter( $r, json_decode( $json_string ) );

	// Save the modified, cleaned format JSON
	// This one is used on the site to show menus to user
	file_put_contents(
		"./json/menus/menu-{$r->id}-{$lang}.json",
		json_encode(
			$menu_json,
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
		)
	);
}

/**
 * Since Louhi doesn't have a JSON file to download, I have to manually parse the menu from the HTML.
 * Well, I don't *have to*. I could just say "No menu". But where's the fun in that?
 * @param $r \Restaurant
 * @param $url string
 */
function fetchLouhiMenu ( $r, $url ) {
	$html_string = file_get_contents( $url );

	// Native PHP class for handling HTML DOM.
	$dom = new DOMDocument;

	// Load the the fetched HTML-string into DOM-handler.
	// mb_convert_encoding(), because loadHTML doesn't seem to read it correctly otherwise.
	$dom->loadHTML( mb_convert_encoding($html_string, 'HTML-ENTITIES', 'UTF-8') );

	// I only need the <div class=content> which contains the menu (and some other stuff I'm not using)
	// From there only get the <p> tags, because all menu items are in one.
	$content_div = $dom->getElementById( 'content' )->getElementsByTagName('p');

	// For finding the correct <p> tags
	// Starting the array at 1, because makes more sense for weekdays (I use the index later on).
	$weekdays = [ 1=>'maanantai', 'tiistai', 'keskiviikko', 'torstai', 'perjantai' ];
	$arr = array();

	/*
	 * We go through all <p> tags found, and try to match the ones that are in the $weekdays-array above.
	 * The website menu uses Finnish weekdays as header for each day.
	 * For comparison we use trim() and strtolower().
	 * We first have to replace any &nbsp; characters, for trim() to work. For some reason, some tags may have them.
	 */
	foreach ( $content_div as $key => $node ) {

		$regular_spaces = preg_replace('/\xc2\xa0/', ' ', $node->nodeValue);
		$nodevalue = strtolower( trim($regular_spaces) );

		if ( in_array( $nodevalue, $weekdays ) ) {
			$arr[] = [ $key , $nodevalue ];
		}
	}

	$week = array();
	foreach ( $arr as $tag ) {
		// Get week menus first ad create an array out of them.
		// The menus are the next four (4) <p> tags after the day-name.
		$menus = array();
		for ( $i=1 ; $i<5 ; $i++ ) {
			$menus[] = $content_div[$tag[0]+$i]->nodeValue;
		}

		$menu = array();
		foreach ( $menus as $index => $value ) {
			$menu[] = [
				'name' => null,
				'prices' => null,
				'vegetarian' => null,
				'components' => (array)$value
			];
		}

		$week[] = [
			'date' => null,
			'index' => array_search ($tag[1], $weekdays),
			'dayname' => ucfirst($tag[1]),
			'lunchHours' => [ '10:30', '14:30' ],
			'menu' => $menu
		];
	}
	// Format results into a usable JSON file, same format as with Fazer/Amica ones.
	$louhi = [
		'id' => $r->id,
		'name' => "Ravintola Louhi",
		'url' => $r->website_url->fin,
		'week' => $week
	];

	// Save the modified, cleaned format JSON
	// This one is used on the site to show menus to user
	file_put_contents(
		"./json/menus/menu-{$r->id}-fin.json",
		json_encode(
			$louhi,
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
		)
	);
}

$json = json_decode(
	file_get_contents( "restaurants.json", true )
);

$restaurants = [];
foreach ( $json->restaurants as $obj ) {
	$rest = new Restaurant( $obj );

	// Go through all JSON-URLs.
	// In most cases this means finnish and english JSON files.
	if ( $rest->json_url ) {
		foreach ( $rest->json_url as $lang => $url ) {
			createNewJsonMenu( $rest , $url , $lang );
		}
	}

	// Custom HTML-scraping for Louhi, because they don't have a JSON file. But as an exercise in HTML-scraping
	// and PHP DOM-manipulation, I do it anyways.
	// ... because I can!
	if ( $rest->name == 'Louhi' ) {
		fetchLouhiMenu($rest, $rest->website_url->fin);
	}
}

$settings->updateMenusLastUpdatedDateAndSave();

//header( "Location: https://{$_SERVER['HTTP_HOST']}/". WEB_PATH ."/settings.php?db_updated" );
//$_SESSION['feedback'] = "<p class='info'>All menus fetched. You should now see what there is to eat this week.</p>";
//exit;

