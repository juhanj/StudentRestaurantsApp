<?php declare(strict_types=1);

class Settings {

	/** @var bool */
	public $food;
	/** @var bool */
	public $kela;
	/** @var bool */
	public $onlyJoensuu;

	/** @var array|null [ lat, long ] */
	public $location = null;

	/** @var string */
	public $lang = 'fi';

	public static function getSettings (): Settings {
		$settings = new Settings();

		$settings->food = (isset( $_COOKIE['food'] ))
			? boolval( $_COOKIE['food'] )
			: true;

		$settings->kela = (!empty( $_COOKIE['kela'] ))
			? boolval( $_COOKIE['kela'] )
			: true;

		$settings->onlyJoensuu = (!empty( $_COOKIE['joensuu'] ))
			? boolval( $_COOKIE['joensuu'] )
			: true;

		if ( !empty( $_COOKIE['location'] ) and !empty( $_COOKIE['gps'] ) ) {
			$temp = json_decode( $_COOKIE['gps'] );
			$settings->location = [
				(float)$temp[0],
				(float)$temp[1]
			];
		}
		if ( !empty( $_COOKIE['lang'] ) ) {
			switch ( $_COOKIE['lang'] ) {
				case 'en' :
					$settings->lang = 'en';
					break;
				case 'fi' :
					$settings->lang = 'fi';
					break;
				default :
					$settings->lang = 'fi';
			}
		}

		return $settings;
	}


}
