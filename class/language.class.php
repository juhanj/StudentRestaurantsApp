<?php declare(strict_types=1);

/**
 * Class Language
 * Extends stdClass because we want it's functionality regarding dynamic variables
 * (I think that refers only to the IDE warnings. The code would work just as well without.)
 */
class Language extends stdClass {

	/**
	 * @var string $lang Two character language code ISO 639-1
	 */
	public $lang;

	public $strings = [];

	function __construct( string $lang = '', string $page = '' ) {
		$this->lang = $lang;
		$this->page = $page;
	}

	public static function getLanguageStrings ( string $lang = 'fi', string $page = CURRENT_PAGE ) : Language {
		$l = new Language( $lang, $page );

		/*
		 * Load the whole JSON file for one language, which is a bit
		 * different from the SQL-version where we only load needed strings
		 * from the database
		 */
		$json = json_decode(
			file_get_contents( "lang/lang.json", true )
		);

		/**
		 * This would be a bit cleaner with a database,
		 * but with a small enough JSON file probably won't matter.
		 */
		foreach ( $json as $pageName => $pageStrings ) {
			if ( $pageName === '_common' or $pageName === $page ) {
				foreach ( $pageStrings as $type => $str ) {
					$l->strings[$type] = $str;
				}
			}
		}

		return $l;
	}

	/**
	 * Custom _GET for printing custom backup string, in case something is missing.
	 * @param string $string The title, or type, or header of the wanted string.
	 * @return string Either the correct string or "UNDEFINED {$str}"
	 */
	function __get( $string ) : string {
		if ( !isset($this->strings[$string]->{$this->lang}) ) {
			return "❔{$string}";
		}

		return $this->strings[$string]->{$this->lang};
	}

}
