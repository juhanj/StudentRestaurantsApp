<?php declare(strict_types=1);

class Restaurant {

	/** @var string $id Short version of the name */
	public $id;
	/** @var string Name of the restaurant */
	public $name;
	/** @var stdClass Menu URLs, for differen languages */
	public $website_url;
	/** @var stdClass URL for JSON file, for different languages. */
	public $json_url;
	/** @var bool Does it offer food or not */
	public $food;
	/** @var bool Is it Kela supported (student priced food) */
	public $kela;
	/** @var string */
	public $address;
	/** @var string */
	public $city;

	/** @var stdClass ->lat & ->long */
	public $location;

	/* @var stdClass */
	public $quickMenu;

	/** @var array Monday === 1 */
	public $normalLunchHours;

	/** @var int */
	public $distance;

	public function __construct () {
	}

	public static function buildFromJSON ( stdClass $json ): Restaurant {
		$r = new Restaurant();
		foreach ( $json AS $key => $value ) {
			$r->{$key} = $value;
		}
		return $r;
	}

	public function isOpenRightNow (): bool {
		// Get currentDay as number. Monday == 1, Sunday == 7.
		$currentDay = date( 'N' ) - 1;
		$todayHours = $this->normalLunchHours[$currentDay];

		if ( is_null( $todayHours ) ) {
			return false;
		}

		$lunchEnd = new DateTime( "today {$todayHours[1]}", new DateTimeZone( 'Europe/Helsinki' ) );
		$now = new DateTime( 'now', new DateTimeZone( 'Europe/Helsinki' ) );


		if ( $now > $lunchEnd ) {
			return false;
		}

		return true;
	}

	public function getHoursToday ( Language $language ): string {
		// Get currentDay as number. Monday == 1, Sunday == 7.
		$currentDay = date( 'N' ) - 1;

		$closingTime = new DateTime( "today {$this->normalLunchHours[$currentDay][1]}", new DateTimeZone( 'Europe/Helsinki' ) );
		$now = new DateTime( 'now', new DateTimeZone( 'Europe/Helsinki' ) );

		if ( empty( $this->normalLunchHours[$currentDay] ) ) {
			return "{$language->CLOSED}";
		}

		if ( $now > $closingTime ) {
			return "{$language->CLOSED}";
		}

		return "{$this->normalLunchHours[$currentDay][0]} &ndash; {$this->normalLunchHours[$currentDay][1]}";
	}

	public function getHoursDay ( int $weekDay, Language $language ): string {
		// Get currentDay as number. Monday == 1, Sunday == 7.
		$currentDay = date( 'N' ) - 1;

		if ( $currentDay === 6 ) {
			$weekDay = 0;
		}

		if ( empty( $this->normalLunchHours[$weekDay] ) ) {
			return "{$language->CLOSED}";
		}

		return "{$this->normalLunchHours[$weekDay][0]} &ndash; {$this->normalLunchHours[$weekDay][1]}";
	}

	public function getHoursHTMLString ( Language $lang ) {
		$currentDay = date( 'N' ) - 1;

		$html = '';

		foreach ( $this->normalLunchHours as $index => $day ) {
			$fontWeight = ($currentDay == $index) ? 'today' : '';
			$dayName = "<span class='day-name'>{$lang->{"DAY_" . ($index+1)}}</span>";
			$hours = "<span class='times'>{$day[0]} &ndash; {$day[1]}</span>";
			$html .= "<li class='margins-off {$fontWeight}'>{$dayName}{$hours}</li>";
		}

		$html = "<ol class='opening-hours-list margins-off'>{$html}</ol>";

		return $html;
	}

	public function calcDistance ( array $location ) {

		$earth_radius = 6371;

		$dLat = deg2rad( $location[0] - $this->location->lat );
		$dLon = deg2rad( $location[1] - $this->location->long );

		$a = sin( $dLat / 2 )
			* sin( $dLat / 2 ) + cos( deg2rad( $this->location->lat ) )
			* cos( deg2rad( $location[0] ) )
			* sin( $dLon / 2 )
			* sin( $dLon / 2 );

		$c = 2 * asin( sqrt( $a ) );
		$d = $earth_radius * $c;

		return $d;

	}

}
