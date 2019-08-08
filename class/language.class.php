<?php declare(strict_types=1);

/**
 * Class Language
 * Extends stdClass because we want it's functionality regarding dynamic variables
 * (I think that refers only to the IDE warnings.)
 */
class Language extends stdClass {

	/**
	 * @var string $lang Three character language code ISO 639-2/T
	 */
	public $lang;
	public $page;

	/**
	 * Language constructor.
	 * @param string $lang From PHP $_COOKIES, Three character language code ISO 639-2/T
	 * @param string $page Current page
	 */
	function __construct( string $lang = 'en', string $page = CURRENT_PAGE ) {

		$this->lang = $lang;
		$this->page = $page;

		/*
		 * Load the whole JSON file for one language, which is a bit
		 * different from the SQL-version where we only load needed strings
		 * from the database
		 */
		$json = json_decode(
			file_get_contents( "lang/{$lang}.json", true )
		);

		/**
		 * This would be a bit cleaner with a database,
		 * but with a small enough JSON file probably won't matter.
		 */
		foreach ( $json->pages as $jsonPage ) {
			if ( $jsonPage->page === '_common' or $jsonPage->page === $page ) {

				foreach ( $jsonPage->strings as $type => $str ) {
					$this->{$type} = $str;
				}

			}
		}
	}

	/**
	 * Custom _GET for printing custom backup string, in case something is missing.
	 * @param string $name The title, or type, or header of the wanted string.
	 * @return string Either the correct string or "UNDEFINED {$str}"
	 */
	function __get( $name ) {
		if ( !isset($this->{$name}) ) {
			return "UNDEFINED {$name}";
		}

		return $this->{$name};
	}

}
