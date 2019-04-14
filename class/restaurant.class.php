<?php declare(strict_types=1);

class Restaurant {

    /** @var int Database ID */
	public $id;
    /** @var string Name of the restaurant */
	public $name;
    /** @var string Menu URL, language specific */
	public $website_url;
    /** @var string URL for JSON file, if available. Language specific. */
	public $json_url;
    /** @var bool Does it offer food or not */
	public $food;
    /** @var bool Is it Kela supported (student priced food) */
    public $kela;
	/** @var float Latitude in degrees */
	public $latitude;
	/** @var float Longitude in degrees */
	public $longitude;
    /** @var string */
    public $address;
    /** @var string */
    public $city;

    /** @var \stdClass */
    public $location;

    /* @var \stdClass */
    public $quickMenu;

	/** @var \stdClass */
	public $normalLunchHours;

    /** @var int */
    public $distance;

	private function ceiling( $number, $significance = 1 ) : ?float {
		return ( is_numeric($number) && is_numeric($significance) )
			? (ceil($number/$significance)*$significance)
			: null;
	}

	public function printDistance() : string {
		if (!$this->distance) {
			return '';
		}

		$dist = $this->distance;

		if ( $dist <= 500 ) {
			$dist = $this->ceiling( $dist, 10 );
		} elseif ( $dist > 500 ) {
			$dist = $this->ceiling( $dist, 50 );
		} elseif ( $dist > 1000 ) {
			$dist = $this->ceiling( $dist, 100 );
		} elseif ( $dist > 50000 ) {
			$dist = $this->ceiling( $dist, 5000 );
		} elseif ( $dist > 100000 ) {
			$dist = $this->ceiling( $dist, 10000 );
		}

		if ($dist >= 1000) {
			$unit = "km";
			$dist = $dist / 1000;
			$dist = fNumber( $dist, ($dist >= 5 ? 1 : 0) );
		}
		else {
			$unit = "m";
			$dist = fNumber($dist, 0);
		}


		return "({$dist}&nbsp;{$unit})";
	}

	public function printMenuLink() : string {
		return ( !empty( $this->json_url ) )
			? "<a href='menu.php?id={$this->id}' class='button'><i class='material-icons'>restaurant_menu</i></a>"
			: "<a href='{$this->website_url}' class='button'><i class='material-icons'>link</i></a>";
	}

	public function printHours( stdClass $hours, stdClass $lang ) : string {
		if ( empty( $hours ) ) {
			return "<i class='material-icons' style='color: firebrick;'>close</i>{$lang->R_LIST_HOURS_CLOSED}";
		}

		return "{$hours->lunch_open} &ndash; {$hours->lunch_close}";
	}

	public function fetchNormalLunchHours( DBConnection $db ) {
		$sql = "select day_index, open, close, lunch_open, lunch_close   
				from openinghours
				where restaurant_id = ?";

		$this->normalLunchHours = $db->query( $sql, [ $this->id ], FETCH_ALL );
	}

	/**
	 * @param string $lang From browser cookies
	 */
	public function fetchQuickMenu ( string $lang ) {
		if ( !$this->food ) { return; }
		if ( !$this->json_url and $this->name != 'Louhi' ) { return; }

		// Get currentDay as number. Sunday == 0.
		$currentDay = idate( 'w' );

		$string = file_get_contents(
			"menus/menu-{$this->id}-{$lang}.json",
			true
		);
		$json = json_decode( $string );

		foreach ( $json->week as $day ) {
			if ( $day->index == $currentDay ) {
				$this->quickMenu = $day;
			}
		}
	}

	public function prettyPrintQuickMenu () {
		$returnString = '<ul class="day-menu">';

		foreach ( $this->quickMenu->menu as $food ) {
			$foodTitle = !empty($food->name)
				? "<span style='font-weight: bold;'>{$food->name}</span>"
				: '';
			$components = implode('<br>', $food->components);
			$returnString .=
				"<li class='menu-item'>
					{$foodTitle}<br>{$components}
				</li>";
		}

		return $returnString;
	}
}
