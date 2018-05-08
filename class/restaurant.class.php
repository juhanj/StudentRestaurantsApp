<?php
class Restaurant {

    /** @var int */
	public $id = null;
    /** @var string */
	public $name = null;

    /** @var string */
	public $menuUrl = null;
    /** @var string */
	public $menuUrl_JSON = null;

    /** @var array[] */
	public $normalLunchHours = array();

    /** @var bool */
	public $food = null;
    /** @var bool */
    public $kela = null;

    /** @var Location */
    public $location = null;
    /** @var string */
    public $address = null;

    /** @var int */
    public $distance = null;

}
