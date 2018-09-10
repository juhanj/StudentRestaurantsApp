<?php

class Restaurant {

    /** @var int Database ID */
	public $id;
    /** @var string Name of the restaurant */
	public $name;
    /** @var string Menu URL, language specific */
	public $website_url;
    /** @var string URL for JSON file, if available */
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

	/** @var Location */
	public $location;

	/** @var array[] */
	public $normalLunchHours;

    /** @var int */
    public $distance;

	private function ceiling( $number, $significance = 1 ) {
		return ( is_numeric($number) && is_numeric($significance) )
			? (ceil($number/$significance)*$significance)
			: false;
	}

	public function print_distance() {
		if (!$this->distance) {
			return '';
		}

		$unit = "m";

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
			$dist = $dist / 1000;
			$unit = "km";
		}

		$dist = number_format($dist, 0, ",", " ");

		return "({$dist} {$unit})";
	}

	public function print_menu_link() {
		if ( !empty( $this->menuUrl_JSON ) ) {
			return "<a href='menu.php?id={$this->id}'><i class='material-icons'>restaurant_menu</i></a>";
		}
		else {
			return "<a href='{$this->menuUrl}'><i class='material-icons'>link</i></a>";
		}
	}

	public function print_hours( array $hours, stdClass $lang ) {
		if ( empty( $hours ) ) {
			return "<i class='material-icons' style='color: firebrick;'>close</i>{$lang->R_LIST_HOURS_CLOSED}";
		}

		return "{$hours[0]} &ndash; {$hours[1]}";
	}

	public function fetchNormalLunchHours( DByhteys $db ) {
		$sql = "select restaurant_id, day_index, open, close, lunch_open, lunch_close   
				from openinghours
				where restaurant_id = ?";

		$this->normalLunchHours = $db->query( $sql, [ $this->id ], FETCH_ALL );
	}
}
