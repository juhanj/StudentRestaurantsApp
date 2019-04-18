<?php declare(strict_types=1);

class Settings {

	/**
	 * @var \DateTime|null
	 */
	public $menusLastUpdated = null;
	/** @var |null Currently not in use, but man do I have ideas for this one. */
	public $currentGlobalTheme = null;

	/** @var bool */
	public $vege;
	/** @var bool */
	public $food;
	/** @var bool */
	public $kela;

	/** @var array|null [ lat, long ]*/
	public $location = null;

	/** @var string */
	public $lang = 'eng';

	/**
	 * Settings constructor.
	 * @param array $cookies
	 */
	public function __construct ( array $cookies ) {
		$this->sortThroughUserCookies( $cookies );

		$json = json_decode(
			file_get_contents( 'settings.json', true )
		);

		// DateTime can throw exception. If so, just print it to $feedback.
		try {
			$this->menusLastUpdated = $json->db_last_updated
				? new DateTime( $json->db_last_updated )
				: null;
		} catch ( Exception $e ) {
			$_SESSION['feedback'] = "<p class='error'>{$e}</p>";
		}
		$this->currentGlobalTheme = $json->current_global_theme;
	}

	/**
	 * @return string
	 */
	public function printLastMenuUpdatedDate () {

		return !empty($this->menusLastUpdated)
			? $this->menusLastUpdated->format( "Y-m-d H:i:s" )
			: '---';
	}

	public function updateMenusLastUpdatedDateAndSave () {
		$this->menusLastUpdated = new DateTime();

		$this->saveSettings();
	}

	/**
	 * Saves `current_global_theme` and `db_last_updated` to the settings.json file
	 */
	public function saveSettings () {
		$temp = json_decode(
			file_get_contents( 'settings.json', true )
		);

		$this->currentGlobalTheme = $temp->current_global_theme;

		$path = $_SERVER['DOCUMENT_ROOT'] . '/' . WEB_PATH .'/json/settings.json';

		file_put_contents(
			$path,
			json_encode(
				[
					'db_last_updated' => $this->menusLastUpdated->format(DATE_ATOM),
					'current_global_theme' => $this->currentGlobalTheme
				]
			)
		);
	}

	/**
	 * @return bool
	 */
	public function haveMenusBeenUpdatedThisWeek () : bool {
		return !empty($this->menusLastUpdated)
			? $this->menusLastUpdated->format( 'W') == date( 'W')
			: false;
	}

	/**
	 * Checks user cookies and sets class variables for later use.
	 * Also, no user input is actually used, so protects against any such attack.
	 * @param array $cookies
	 */
	private function sortThroughUserCookies ( array $cookies ) {
		// Valid values: False || True
		$this->vege = isset($cookies[ 'vege' ])
			? (bool)$cookies['vege']
			: false;
		// Valid: False || True
		$this->food = isset($cookies[ 'food' ])
			? (bool)$cookies['food']
			: false;
		// Valid: False || True
		$this->kela = isset($cookies[ 'kela' ])
			? (bool)$cookies['kela']
			: false;

		// Valid: False || [decimal,decimal]
		// By default the value is set as False already.
		if ( !empty( $cookies[ 'location' ] ) ) {
			$temp = json_decode( $cookies[ 'location' ] );
			$this->location = [
				(float)$temp[0],
				(float)$temp[1]
			];
		}

		// Valid: 'eng' || 'fin' || ??
		// By default 'eng', if not valid value
		if ( !empty( $cookies[ 'lang' ] ) ) {
			switch ( json_decode($cookies[ 'lang' ]) ) {
				case 'eng' :
					$this->lang = 'eng';
					break;
				case 'fin' :
					$this->lang = 'fin';
					break;
				default :
					$this->lang = 'eng';
			}
		}
	}
}
