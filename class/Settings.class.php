<?php declare(strict_types=1);

class Settings {

	/**
	 * @var \DateTime|null
	 */
	public $menusLastUpdated = null;
	public $currentGlobalTheme = null;

	/**
	 * Settings constructor.
	 * @throws \Exception
	 */
	public function __construct () {
		$json = json_decode(
			file_get_contents( 'settings.json', true )
		);

		$this->menusLastUpdated = $json->db_last_updated
			? new DateTime($json->db_last_updated)
			: null;
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

	public function updateMenusLastUpdatedDate () {
		$this->menusLastUpdated = new DateTime();

		$this->saveSettings();
	}

	public function saveSettings () {
		$temp = json_decode(
			file_get_contents( 'settings.json', true )
		);

		$this->currentGlobalTheme = $temp->current_global_theme;

		$path = $_SERVER['DOCUMENT_ROOT'] . '/superduperstucaapp/json/settings.json';

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

	public function hasMenusBeenUpdatedThisWeek () {
		return !empty($this->menusLastUpdated)
			? $this->menusLastUpdated->format( 'W') == date( 'W')
			: false;
	}
}
