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

    function __construct ( $foo, $res ) {
        $this->date = $foo->Date;

        $properDate = DateTime::createFromFormat('Y-m-d\TH:i:sT', $this->date);
        $this->index = $properDate->format('N')-1;
        $this->dayName = $properDate->format('l');

        $temp = explode("-", $foo->LunchTime);

        if (count($temp) != 2 AND count($temp[0]) != 5 AND count($temp[1]) != 5) {
            $temp = !empty( $foo->SetMenus ) ? $res->normalLunchHours[$this->index] : null;
        } else {
            $temp[0] = DateTime::createFromFormat("H.i", $temp[0])->format("H:i");
            $temp[1] = DateTime::createFromFormat("H.i", $temp[1])->format("H:i");
        }

        $this->lunchHours = $temp;

        foreach ( $foo->SetMenus as $key => $value ) {
            $this->menu[$key] = new Food( $value );
        }
    }

}
