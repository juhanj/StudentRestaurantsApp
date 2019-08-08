<?php declare(strict_types=1);

class Restaurant {

	/** @var int Database ID */
	public $id;
	/** @var string Name of the restaurant */
	public $name;
	/** @var \stdClass Menu URLs, for differen languages */
	public $website_url;
	/** @var \stdClass URL for JSON file, for different languages. */
	public $json_url;
	/** @var bool Does it offer food or not */
	public $food;
	/** @var bool Is it Kela supported (student priced food) */
	public $kela;
	/** @var string */
	public $address;
	/** @var string */
	public $city;

	/** @var \stdClass -->lat & -->long */
	public $location;

	/* @var \stdClass */
	public $quickMenu;

	/** @var array Monday === 1 */
	public $normalLunchHours;

	/** @var int */
	public $distance;

	public function __construct ( stdClass $json ) {
		foreach ( $json AS $key => $value ) {
			$this->{$key} = $value;
		}
	}

	private function ceiling ( $number , $significance = 1 ): ?float {
		return (is_numeric( $number ) && is_numeric( $significance ))
			? (ceil( $number / $significance ) * $significance)
			: null;
	}

	public function printDistance (): string {
		if ( !$this->distance ) {
			return '(561&nbsp;m)';
		}

		$dist = $this->distance;

		if ( $dist <= 500 ) {
			$dist = $this->ceiling( $dist , 10 );
		}
		elseif ( $dist > 500 ) {
			$dist = $this->ceiling( $dist , 50 );
		}
		elseif ( $dist > 1000 ) {
			$dist = $this->ceiling( $dist , 100 );
		}
		elseif ( $dist > 50000 ) {
			$dist = $this->ceiling( $dist , 5000 );
		}
		elseif ( $dist > 100000 ) {
			$dist = $this->ceiling( $dist , 10000 );
		}

		if ( $dist >= 1000 ) {
			$unit = "km";
			$dist = $dist / 1000;
			$dist = fNumber( $dist , ($dist >= 5 ? 1 : 0) );
		}
		else {
			$unit = "m";
			$dist = fNumber( $dist , 0 );
		}

		return "({$dist}&nbsp;{$unit})";
	}

	public function printMenuLink ( Language $lang ) : string {
		if ( empty($this->website_url->{$lang->lang}) ) { return ''; }

		return (!empty( $this->json_url->{$lang->lang} ))
			? "<a href='menu.php?id={$this->id}' class='button'><i class='material-icons'>restaurant_menu</i></a>"
			: "<a href='{$this->website_url->{$lang->lang}}' class='button'><i class='material-icons'>link</i></a>";
	}

	public function printListLinks ( Language $lang ) : string {
		/*
		 * Print two button-like links
		 * 1. Link to website
		 *      - check language, print finnish, if chosen $lang not available
		 * 2. Link to on-site menu
		 *      - check language, print finnish, if chosen $lang not available
		 */

		if ( !empty($this->website_url->{$lang->lang}) ) {
			$website_link =
				"<a href='{$this->website_url->{$lang->lang}}' class='button'><i class='material-icons'>link</i></a>";
		}
		else {
			$website_link =
				"<a href='{$this->website_url->fi}' class='button'>
					<i class='material-icons'>link</i>🇫🇮
				</a>";
		}

		if ( !empty($this->json_url->{$lang->lang}) ) {
			$menu_link =
				"<a href='menu.php?id={$this->id}' class='button'><i class='material-icons'>restaurant_menu</i></a>";
		}
		else if ( !empty($this->json_url->fi) or $this->name == 'Louhi' ) {
			$menu_link =
				"<a href='menu.php?id={$this->id}' class='button'><i class='material-icons'>restaurant_menu</i>🇫🇮</a>";
		}
		else {
			$menu_link =
				"<button class='button disabled' disabled><i class='material-icons'>restaurant_menu</i></button>";
		}

		return $website_link . $menu_link;
	}

	public function printHours ( int $i , Language $lang ): string {
		if ( empty( $this->normalLunchHours[$i] ) ) {
			return "<i class='material-icons' style='color: firebrick;'>close</i>{$lang->R_LIST_HOURS_CLOSED}";
		}

		return "{$this->normalLunchHours[$i][0]} &ndash; {$this->normalLunchHours[$i][1]}";
	}

	/**
	 * @param string $lang From browser cookies
	 */
	public function fetchQuickMenu ( string $lang ) {
		// Check that there actually is something to fetch.
		// Louhi is a special case, since that menu is fetched by parsing HTML directly.
		if ( !$this->food ) { return; }
		if ( !$this->json_url and $this->name != 'Louhi' ) { return; }

		// Get currentDay as number. Sunday == 7.
		$currentDay = date('N');

		if ( $this->normalLunchHours[$currentDay-1] === null ) { return; }

		if ( $this->name == 'Louhi' or empty($this->json_url->{$lang}) ) {
			$filename = "menus/menu-{$this->id}-fi.json";
		}
		else {
			$filename = "menus/menu-{$this->id}-{$lang}.json";
		}

		$string = file_get_contents(
			$filename,
			true
		);
		$json = json_decode( $string );

		$this->quickMenu = new stdClass();

		foreach ( $json->week as $index => $day ) {
			if ( $day->index == $currentDay ) {
				$this->quickMenu->today = $day;

				$this->quickMenu->tomorrow = ($index==6)
					? $json->week[$index+1]
					: null;
			}
		}
	}

	public function prettyPrintQuickMenu () {

		$listItems = '';
		foreach ( $this->quickMenu->today->menu as $food ) {
			$foodTitle = !empty( $food->name )
				? "<span style='font-weight: bold;'>{$food->name}</span>"
				: '';
			$components = implode( '<br>' , $food->components );
			$listItems .=
				"<li class='menu-item'>
					{$foodTitle}<br>{$components}
				</li>";
		}

		return  "<ul class='day-menu'>
			<h2>{$this->quickMenu->today->dayname}</h2>
			{$listItems}</ul>";
	}
}
