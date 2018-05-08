<?php
class MenuJSON {

    /** @var int */
    public $id = null;
    /** @var string */
    public $name = null;
    /** @var string */
    public $url = null;

    /** @var Day[] */
    public $week = array();

    function __construct ( $res, $foo ) {
        $this->id = $res->id;
        $this->name = $foo->RestaurantName;
        $this->url = $foo->RestaurantUrl;

        foreach ( $foo->MenusForDays as $key => $value ) {
            $this->week[$key] = new Day( $value, $res );
        }
    }
}
