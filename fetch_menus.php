<?php declare(strict_types=1);
require $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/studentrestaurantsapp/components/_start.php';
/**
 * @var Language $lang
 */

/**
 * @param $r Restaurant
 * @param $url string
 * @param $language string
 */
function createNewJsonMenu ( $r, $url, $language ) {
	// Fetch weekly menu as JSON from Fazer servers:
	$json_string = file_get_contents( $url );
	// Save the original, for debugging purposes
	file_put_contents( "./json/menus/orig-{$r->id}-{$language}.json", $json_string );

	// Formatting the JSON a bit to be more useful and easy to use for me.
	$menu_json = new JSONMenuFormatter( $r, json_decode( $json_string ) );

	// Save the modified, cleaned format JSON
	// This one is used on the site to show menus to user
	file_put_contents(
		"./json/menus/{$r->id}-{$language}.json",
		json_encode(
			$menu_json,
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
		)
	);
}

/**
 * Since Louhi doesn't have a JSON file to download, I have to manually parse the menu from the HTML.
 * Well, I don't *have to*. I could just say "No menu available". But where's the fun in that?
 * @param $r Restaurant
 * @param $url string
 * @param $lang Language
 */
function fetchLouhiMenu ( $r, $url, $lang ) {
	$html_string = file_get_contents( $url );

	// Native PHP library for handling HTML DOM.
	$dom = new DOMDocument;

	// Load the the fetched HTML-string into DOM-handler.
	// mb_convert_encoding(), because loadHTML doesn't seem to read it correctly otherwise.
	$dom->loadHTML( mb_convert_encoding( $html_string, 'HTML-ENTITIES', 'UTF-8' ) );

	// I only need the <div class=content> which contains the menu (and some other stuff I'm not using)
	// From there only get the <p> tags, because all menu items are in one.
	$content_div = $dom->getElementById( 'content' )->getElementsByTagName( 'p' );

	// For finding the correct <p> tags
	// Starting the array at 1, because makes more sense for weekdays (I use the index later on).
	$weekdays = [ 1 => 'Maanantai', 'Tiistai', 'Keskiviikko', 'Torstai', 'Perjantai' ];
	$day_tag_indexes = array();

	/*
	 * We go through all <p> tags found, and try to match the ones that are in the $weekdays-array above.
	 * The website menu uses Finnish weekdays as header for each day.
	 * For comparison we use trim() and strtolower().
	 * We first have to replace any &nbsp; characters, for trim() to work. For some reason, some tags may have them.
	 */
	foreach ( $content_div as $key => $node ) {
		$start_of_content = mb_substr(
			$node->nodeValue, 0, 15
		);

		foreach ( $weekdays as $index => $day ) {
			$result = mb_strpos( $start_of_content, $day );

			if ( $result > -1 ) {
				$day_tag_indexes[] = [ $key, $index, $day ];
			}
		}
	}

	$week = array();
	$english_week = array();
	foreach ( $day_tag_indexes as $tag ) {
		// Get week menus first ad create an array out of them.
		// The menus are the next four (4) <p> tags after the day-name.
		$menus = array();
		for ( $i = 1; $i < 5; $i++ ) {

			$dom_element = $content_div[$tag[0] + $i];

			$extra_food_info = '';
			if ( $dom_element->childNodes->length === 3 ) {
				foreach ( $dom_element->childNodes as $index => $node ) {
					$regular_spaces = str_replace( "\xc2\xa0", ' ', $node->nodeValue );
					$regular_spaces = trim( $regular_spaces );
					if ( $index === 0 ) {
						$start = strpos( $regular_spaces, '(' );
						$end = strpos( $regular_spaces, ')', $start + 1 );
						$length = $end - $start;

						$extra_food_info = substr( $regular_spaces, $start + 1, $length - 1 );

						$menus[$i][0] = $regular_spaces;
					} else if ( $index === 1 ) {
						$menus[$i][1] = $regular_spaces . " ({$extra_food_info})";
					} else if ( $index === 2 ) {
						$menus[$i][2] = $regular_spaces;
					}
				}
			} else {
				$regular_spaces = str_replace( "\xc2\xa0", ' ', $dom_element->nodeValue );
				$regular_spaces = trim( $regular_spaces );

				$menus[$i][0] = $regular_spaces;
				$menus[$i][1] = $regular_spaces;
				$menus[$i][2] = '';
			}

		}

		$menu = array();
		$english_menu = array();
		foreach ( $menus as $index => $value ) {
			$menu[] = [
				'name' => null,
				'prices' => $value[2],
				'vegetarian' => null,
				'components' => array( $value[0] )
			];
			$english_menu[] = [
				'name' => null,
				'prices' => $value[2],
				'vegetarian' => null,
				'components' => array( $value[1] )
			];
		}

		$english_day_name = $lang->{"DAY_{$tag[1]}_LONG"};

		$week[] = [
			'date' => null,
			'index' => $tag[1],
			'dayname' => $tag[2],
			'lunchHours' => [ '10:30', '14:30' ],
			'menu' => $menu
		];
		$english_week[] = [
			'date' => null,
			'index' => $tag[1],
			'dayname' => $english_day_name,
			'lunchHours' => [ '10:30', '14:30' ],
			'menu' => $english_menu
		];
	}
	// Format results into a usable JSON file, same format as with Fazer/Amica ones.
	$louhi_finnish = [
		'id' => $r->id,
		'name' => "Ravintola Louhi",
		'url' => $r->website_url->fi,
		'week' => $week
	];
	$louhi_english = [
		'id' => $r->id,
		'name' => "Ravintola Louhi",
		'url' => $r->website_url->fi,
		'week' => $english_week
	];

	// Save the modified, cleaned format JSON
	// This one is used on the site to show menus to user
	file_put_contents(
		"./json/menus/{$r->id}-fi.json",
		json_encode(
			$louhi_finnish,
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
		)
	);
	file_put_contents(
		"./json/menus/{$r->id}-en.json",
		json_encode(
			$louhi_english,
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
		)
	);
}

$json = json_decode(
	file_get_contents( "restaurants.json", true )
);

$settings->getLastMenuUpdatedDate();
$difference = time() - $settings->menusLastUpdated;

if ( $difference < 3600 ) {
	header( "Location: " . WEB_PATH . "/settings.php?dbupdated" );
	$_SESSION['feedback'] = "<p class='info'>{$lang->MENUS_ALREADY_UPDATED}</p>";
	exit;
}

$restaurants = [];
foreach ( $json->restaurants as $obj ) {
	$rest = Restaurant::buildFromJSON( $obj );

	// Go through all JSON-URLs.
	// In most cases this means Finnish and English JSON files.
	if ( $rest->json_url ) {
		foreach ( $rest->json_url as $url_lang => $url ) {
			createNewJsonMenu( $rest, $url, $url_lang );
		}
	}

	$lang->lang = 'en';

	// Custom HTML-scraping for Louhi, because they don't have a JSON file. But as an exercise in HTML-scraping
	// and PHP DOM-manipulation, I do it anyways.
	// ... because I can!
	if ( $rest->id == 'louhi' ) {
		fetchLouhiMenu( $rest, $rest->website_url->fi, $lang );
	}

	$lang->lang = $settings->lang;
}

header( "Location: " . WEB_PATH . "/settings.php?db_updated" );
$_SESSION['feedback'] = "<p class='success'>{$lang->MENU_UPDATE_SUCCESS}</p>";
exit;
