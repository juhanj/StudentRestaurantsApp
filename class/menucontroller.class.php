<?php declare(strict_types=1);

/**
 * Class MenuController
 */
class MenuController implements Controller {

	/**
	 * @var mixed
	 */
	public $result = null;

	/**
	 * @param array $req
	 */
	public function handleRequest ( array $req ) {
		switch ( $req['request'] ?? null ) {
			case 'check_for_update':
				$result = $this->requestCheckForUpdate( $req );
				break;
			case 'force_update':
				$result = $this->requestForceUpdate( $req );
				break;
			default:
				$result = false;
				$this->setError( 0, 'Invalid request' );
		}

		$this->result['success'] = $result;
	}

	/**
	 * @param int $id
	 * @param string $msg
	 */
	public function setError ( int $id, string $msg ) {
		$this->result = [
			'error' => true,
			'err' => $id,
			'errMsg' => $msg,
		];
	}

	public function fetchAmicaMenu ( Restaurant $restaurant, string $language ) {
		// fetch original json menu from website
		$json_string = file_get_contents( $restaurant->json_url->{$language} );
		file_put_contents( "./json/menus/{$restaurant->id}-{$language}-orig.json", $json_string );

		// format the menu to my liking, for easier usage
		$menu = new JSONMenuFormatter( $restaurant, json_decode( $json_string ) );
		$menu_json = json_encode( $menu, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK );

		// save menu
		file_put_contents(
			"./json/menus/{$restaurant->id}-{$language}.json",
			$menu_json
		);

		return $menu_json;
	}

	public function fetchLouhiMenu () {
		$json = json_decode( file_get_contents( "restaurants.json", true ) );

		$r = Restaurant::buildFromJSON( $json->restaurants->louhi );
		$html_string = file_get_contents( $r->website_url->fi );

		// Native PHP library for handling HTML DOM.
		$dom = new DOMDocument;

		// Load the the fetched HTML-string into DOM-handler.
		// mb_convert_encoding(), because loadHTML doesn't seem to read it correctly otherwise.
		@$dom->loadHTML( mb_convert_encoding( $html_string, 'HTML-ENTITIES', 'UTF-8' ) );

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
					'components' => array( $value[0] )
				];
				$english_menu[] = [
					'name' => null,
					'prices' => $value[2],
					'components' => array( $value[1] )
				];
			}

			$week[] = [
				'date' => null,
				'index' => $tag[1],
				'lunchHours' => [ '10:30', '14:30' ],
				'menu' => $menu
			];
			$english_week[] = [
				'date' => null,
				'index' => $tag[1],
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

		$louhi_menus_json = [
			'fi' => json_encode( $louhi_finnish, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK ),
			'en' => json_encode( $louhi_english, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK ),
		];

		// Save the modified, cleaned format JSON
		// This one is used on the site to show menus to user
		file_put_contents(
			"./json/menus/{$r->id}-fi.json",
			$louhi_menus_json['fi']
		);
		file_put_contents(
			"./json/menus/{$r->id}-en.json",
			$louhi_menus_json['en']
		);

		return $louhi_menus_json;
	}

	public function requestCheckForUpdate ( $options ) {
		$json = json_decode( file_get_contents( "restaurants.json", true ) );

		$r = Restaurant::buildFromJSON( $json->restaurants->{$options['id']} );

		$filename = "./json/menus/{$r->id}-{$options['lang']}.json";

		if ( file_exists( $filename ) ) {
			$file_last_updated = filemtime( $filename );
			if ( date( 'W', $file_last_updated ) === date( 'W' ) ) {
				$this->result = [
					'id' => 1,
					'msg' => 'Menu already updated'
				];
				return true;
			}
		}

		if ( $r->id === 'louhi' ) {
			$menu = $this->fetchLouhiMenu()[$options['lang']];
		}
		else {
			$menu = $this->fetchAmicaMenu( $r, $options['lang'] );
		}

		$this->result = [
			'id' => 2,
			'msg' => 'Menu updated',
			'menu' => $menu,
		];

		return true;
	}

	public function requestForceUpdate ( $options ) {
		$json = json_decode( file_get_contents( "restaurants.json", true ) );

		$r = Restaurant::buildFromJSON( $json->restaurants->{$options['id']} );

		if ( $r->id === 'louhi' ) {
			$menu = $this->fetchLouhiMenu()[$options['lang']];
		}
		else {
			$menu = $this->fetchAmicaMenu( $r, $options['lang'] );
		}

		$this->result = [
			'id' => 1,
			'msg' => 'Menu updated',
			'menu' => $menu,
		];

		return true;

	}
}
