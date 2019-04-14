<?php
class Day {

    /** @var string */
    public $date = null;
    /** @var int */
    public $index = null;
    /** @var string */
    public $dayName = null;
    /** @var string[] */
    public $lunchHours = array();

    /** @var Food[] */
    public $menu = array();

    function __construct ( $res, $menu ) {
    	debug( $res );
    	debug( $menu );

        $this->date = $menu->Date;

        $properDate = DateTime::createFromFormat('Y-m-d\TH:i:sT', $this->date);
        $this->index = $properDate->format('N');
        $this->dayName = $properDate->format('l');

        $temp = explode( "-", $menu->LunchTime);

        if (count($temp) != 2 AND count($temp[0]) != 5 AND count($temp[1]) != 5) {
            $temp = !empty( $menu->SetMenus ) ? $res->normalLunchHours[ $this->index] : null;
        } else {
	        $temp[0] = str_replace( '.', ':', $temp[0]);
	        $temp[1] = str_replace( '.', ':', $temp[1]);
        }

        $this->lunchHours = $temp;

        foreach ( $menu->SetMenus as $key => $value ) {
            $this->menu[$key] = new Food( $value );
        }
    }

}
