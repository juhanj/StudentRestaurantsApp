<?php

class Language extends stdClass {

	function __construct( DByhteys $db, string $lang = null, string $page = null ) {

		if ( $lang == null ) {
			$lang = !empty( $_COOKIE[ 'lang' ] ) ? $_COOKIE[ 'lang' ] : 'eng';
			$page = basename( $_SERVER[ 'SCRIPT_NAME' ] , '.php' );
		}

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
