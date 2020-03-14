<?php declare(strict_types=1);

class JSONMenuFormatter {

    /** @var int */
    public $id = null;
    /** @var string */
    public $name = null;
    /** @var string */
    public $url = null;

    /** @var array */
    public $week = array();

	/**
	 * JSONMenuFormatter constructor.
	 * @param $res Restaurant
	 * @param $json stdClass
	 */
    function __construct ( $res, $json ) {
        $this->id = $res->id;
        $this->name = $json->RestaurantName;
        $this->url = $json->RestaurantUrl;

        foreach ( $json->MenusForDays as $index => $weekday ) {
	        $this->week[$index] = $this->formatDay( $weekday );
        }
    }

	/**
	 * @param $day
	 * @return array
	 */
	public function formatDay ( $day ) : array {

		// Needed for easily getting weekday-index and day-name
	    $properDate = DateTime::createFromFormat( 'Y-m-d\TH:i:sT', $day->Date);

	    // Formatting the different foods in a days menu
	    $menu = array();
		foreach ( $day->SetMenus as $key => $originalMenu ) {
			$menu[$key] = $this->formatMenu( $originalMenu );
		}

	    return [
		    'date' => $day->Date,
		    'index' => $properDate->format('N'),
		    'lunchHours' => $day->LunchTime ? $this->formatLunchHours( $day->LunchTime ) : null,
		    'menu' => $menu
	    ];

    }

    public function formatLunchHours ( string $str ) : ?array {
		// "10.00 - 15.00" --> ['10.00','15.00']
	    $new_lunchhours = array_map('trim', explode('-', $str));

	    // Check that valid hours, and return null if not.
	    if ( count($new_lunchhours) != 2
	        or strlen($new_lunchhours[0]) != 5
	        or strlen($new_lunchhours[1]) != 5) {

	    	return null;

	    	// No idea what the idea below was, but it's replacing the time with default opening hours.
		    // Not deleting it, in case it was for some weird edge-case scenario. --JJ190411
		    //$new_lunchhours = !empty( $day->SetMenus )
			//    ? $this->getNormalLunchHours( $db, $dayIndex )
			//    : null;
	    }

        return [
		    str_replace( '.', ':', $new_lunchhours[0]),
		    str_replace( '.', ':', $new_lunchhours[1])
	    ];
    }

    function formatMenu ( $ogMenu ) : array {
		return [
			'name' => ucfirst(mb_strtolower($ogMenu->Name ?? '')),
			'prices' => $ogMenu->Price,
	        'components' => $ogMenu->Components,
		];
    }
}
