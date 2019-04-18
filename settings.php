<?php declare(strict_types=1);
require __DIR__ . '/components/_start.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang->lang ?>">

<?php require 'html-head.php'; ?>

<body class="grid">

<?php require 'html-header.php'; ?>

<main class="main-body-container">

	<div class="feedback" id="feedback"><?= check_feedback_POST() ?></div>

	<a href="index.php" class="button return"><?= $lang->RETURN_INDEX ?></a>

	<div class="settings">
		<label>
			<input type="checkbox" id="vegetarian" name="vege"
				<?= $settings->vege ? 'checked' : '' ?>>
			<span>
				<?= $lang->SETT_VEGE ?><br>
				<?= $lang->SETT_VEGE_INFO ?>
			</span>
		</label>

		<label>
			<input type="checkbox" id="cafes" name="food"
				<?= $settings->food ? 'checked' : '' ?>>
			<span>
				<?= $lang->SETT_FOOD ?><br>
				<?= $lang->SETT_FOOD_INFO ?>
			</span>
		</label>

		<label>
			<input type="checkbox" id="kela" name="kela"
				<?= $settings->kela ? 'checked' : '' ?>>
			<span>
				<?= $lang->SETT_KELA ?><br>
				<?= $lang->SETT_KELA_INFO ?>
			</span>
		</label>
	</div>

	<div class="settings" id="location">
		<label>
			<input type="checkbox" id="location" name="location"
				<?= $settings->location ? 'checked' : '' ?>>
			<span>
				<?= $lang->SETT_LOC ?><br>
				<?= $lang->SETT_LOC_INFO ?>
			</span>
		</label>
	</div>

	<div class="settings" id="languages">
		<h2 class="settings-head"><?= $lang->SETT_LANG_HEAD ?></h2>
		<p><?= $lang->SETT_LANG_INFO ?></p>

		<label for="english">
			<input type="radio" id="english" name="lang" value="eng"
				<?= $settings->lang == 'eng' ? 'checked' : '' ?>>
			<?= $lang->SETT_LANG_ENG ?>
		</label>

		<label for="finnish">
			<input type="radio" id="finnish" name="lang" value="fin"
				<?= $settings->lang == 'fin' ? 'checked' : '' ?>>
			<?= $lang->SETT_LANG_FIN ?>
		</label>
	</div>

	<div class="settings">
		<a href="fetch_menus.php" class="button">
			<span>
				<?= $lang->SETT_DB_UPDATE ?>
				<i class="material-icons">refresh</i>
			</span><br>
			<span><?= $lang->SETT_DB_UPDATE_INFO ?></span>
			<p><?= $lang->SETT_DB_UPDATE_LAST_DATE ?>:
				<?= $settings->printLastMenuUpdatedDate() ?></p>
		</a>
	</div>

</main>

<?php require 'html-footer.php'; ?>

<script>
	/**
	 * Save boolean value of a setting
	 */
	function saveSetting(element) {
		setCookie(
			element.target.name,
			JSON.stringify(Number(element.target.checked)),
			999
		);
	}
	/**
	 * Save value value of the chosen language radio button
	 **/
	function saveLanguage(element) {
		setCookie(
			element.target.name,
			JSON.stringify(element.target.value),
			999
		);
	}
	/**
	 * When user clicks on the checkbox for location,
	 * ask for permission, and getLocation.
	 **/
	function getLocation(element) {
		//TODO: do location stuff here.
	}

	/**
	 * Needs setCookie from main.js file (in header, deferred)
	 * And getLocation (assuming I have implemented it)
	 */
	window.onload = () => {

		// Loop through all `input[type=checkbox]:not(#location)` elements.
		// Not location, because it works a bit differently.
		// So easy, gotta love not having to support IE.
		document.querySelectorAll("input[type=checkbox]:not(#location)").forEach((input) => {
			// Save cookie from currently set value
			setCookie(input.dataset.name, JSON.stringify(Number(input.checked)), 999);
			// Add a listener for user made changes
			input.addEventListener('click', saveSetting);
		});

		let currentLanguage = document.querySelector("input[name='lang']:checked");
		setCookie( currentLanguage.dataset.name, JSON.stringify(currentLanguage.checked), 999 );

		document.querySelectorAll("input[name='lang']").forEach((input) => {
			// Add a listener for user made changes
			input.addEventListener('click', saveLanguage);
		});

		setCookie( 'location', 0 )
	}
</script>

</body>
</html>
