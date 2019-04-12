<?php declare(strict_types=1);
require $_SERVER[ 'DOCUMENT_ROOT' ] . '/superduperstucaapp/components/_start.php';

function createNewJson ( $r ) {
	// Fetch weekly menu as JSON from Fazer servers:
	$json_string = file_get_contents($r->json_url);
	// Save the original, For testing purposes
	file_put_contents("./json/menus/menu-orig-{$r->id}-{$r->language}.json", $json_string );

	// Formatting the JSON a bit to be more useful and easy to use for me.
	$menu_json = new JSONMenuFormatter( $r, json_decode( $json_string ) );

	// Save the modified, cleaned format JSON
	// This one is used on the site to show menus to user
	file_put_contents(
		"./json/menus/menu-{$r->id}-{$r->language}.json",
		json_encode(
			$menu_json,
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
		)
	);
}

function fetchLouhiMenu ( $r ) {
	$html_string = file_get_contents( $r->website_url );

	// Native PHP class for handling HTML DOM.
	$dom = new DOMDocument;

	// Load the the fetched HTML-string into DOM-handler.
	// mb_convert_encoding(), because loadHTML doesn't seem to read it correctly otherwise.
	$dom->loadHTML( mb_convert_encoding($html_string, 'HTML-ENTITIES', 'UTF-8') );

	// I only need the <div class=content> which contains the menu (and some other stuff I'm not using)
	// From there only get the <p> tags, because all menu items are in one.
	$content_div = $dom->getElementById( 'content' )->getElementsByTagName('p');

	// For finding the correct <p> tags
	$weekdays = [ 1=>'maanantai', 'tiistai', 'keskiviikko', 'torstai', 'perjantai' ];
	$arr = array();

	/*
	 * We go through all <p> tags found, and try to match the ones that are in the $weekdays-array above.
	 * The website menu uses Finnish weekdays as header for each day.
	 * For comparison we use trim() and strtolower().
	 * We first have to replace any &nbsp; characters, for trim() to work. For some reason, some days may have them.
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
		'url' => $r->website_url,
		'week' => $week
	];

	// Save the modified, cleaned format JSON
	// This one is used on the site to show menus to user
	file_put_contents(
		"./json/menus/menu-{$r->id}-{$r->language}.json",
		json_encode(
			$louhi,
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
		)
	);
}

$sql = "select restaurant_id as id, website_url, m.json_url, language 
		from menuurls m
		inner join
		    restaurant r on m.restaurant_id = r.id
		where m.json_url is not null 
			and food is true";

$restaurants = $db->query( $sql, [], FETCH_ALL );


foreach ($restaurants as $r) {
	createNewJson( $r );
}

// Lastly, custom HTML scraping for Louhi...
$sql = "select id, website_url, language 
		from restaurant r 
	    inner join menuurls m on r.id = m.restaurant_id
	    where id = 8 and name = 'Louhi'";

$louhi = $db->query( $sql );
fetchLouhiMenu( $louhi );

$settings->updateMenusLastUpdatedDate();

//header( "Location: http://{$_SERVER['HTTP_HOST']}/superduperstucaapp/settings.php?db_updated" );
//$_SESSION['feedback'] = "<p class='info'>All menus fetched. You should now see what there is to eat this week.</p>";
//exit;

