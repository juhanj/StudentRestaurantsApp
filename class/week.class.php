<?php declare(strict_types=1);

class Week {

	/** @var \stdClass ->date string, ->index int, ->dayName string, ->lunchHours [string], menu */

	/** @var string */
	public $date;
	/** @var int */
	public $index;
	/** @var string */
	public $dayName;
	/** @var string[] */
	public $lunchHours;

	/** @var \stdClass ->name string, ->prices string, ->vegetarian bool, ->components [string] */
	public $menu;

}
