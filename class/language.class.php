<?php declare(strict_types=1);

class Language extends stdClass {

	/**
	 * @var string $lang Three character language code ISO 639-2/T
	 */
	public $lang;

	function __construct( DByhteys $db, string $lang, string $page ) {

		$this->lang = $lang;

		$sql = "select txt_type, txt 
				from lang
				where lang = ? 
				  and (txt_page = ? or txt_page = '_common')";
		$rows = $db->query( $sql, [ $lang, $page ], DByhteys::FETCH_ALL );

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
