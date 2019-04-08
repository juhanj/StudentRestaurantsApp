<?php declare(strict_types=1);

class Language extends stdClass {

	/**
	 * @var string $lang Three character language code ISO 639-2/T
	 */
	public $lang;
	public $page;

	function __construct( DBConnection $db, string $lang = 'eng', string $page = CURRENT_PAGE ) {

		$this->lang = $lang;
		$this->page = $page;

		$sql = "select txt_type, txt 
				from lang
				where lang = ? 
				  and (txt_page = ? or txt_page = '_common')";
		$rows = $db->query( $sql, [ $lang, $page ], FETCH_ALL );

		foreach ( $rows as $row ) {
			$this->{$row->txt_type} = $row->txt;
		}

	}

	function __get( $name ) {
		if ( !isset($this->{$name}) ) {
			return "UNDEFINED {$name}";
		}

		return $this->{$name};
	}

}
