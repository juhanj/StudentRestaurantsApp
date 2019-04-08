<?php declare(strict_types=1);
require $_SERVER['DOCUMENT_ROOT'] . '/superduperstucaapp/components/_start.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang->lang ?>">

<?php require 'html-head.php'; ?>

<body>

<?php require 'html-header.php'; ?>

<main class="main-body-container">

	<form class="settings">
		<fieldset><legend><?= $lang->REST_FIELDSET_LEG ?></legend>
			<label>
				<input type="checkbox" id="vegetarian" data-name="vege" checked>
				<span><?= $lang->SETTING_1 ?></span>
			</label>

			<label>
				<input type="checkbox" id="cafes" data-name="food" checked>
				<span><?= $lang->SETTING_2 ?></span>
			</label>

			<label>
				<input type="checkbox" id="kela" data-name="kela" checked>
				<span><?= $lang->SETTING_3 ?><br><?= $lang->SETTING_3_INFO ?></span>
			</label>
		</fieldset>
		<fieldset><legend><?= $lang->LOC_FIELDSET_LEG ?></legend>
			<label>
				<input type="checkbox" id="location" data-name="location" disabled>
				<span><?= $lang->SETTING_4 ?><br><?= $lang->SETTING_4_INFO ?></span>
			</label>
		</fieldset>
	</form>

	<fieldset class="db_update"><legend><?= $lang->DB_UPD_FIELDSET_LEG ?></legend>
		<p>
			<a href="fetch_menus.php"><?= $lang->SETTING_DB_UPDATE ?>
				<i class="material-icons" style="margin-bottom: 0;">refresh</i>
			</a>
		</p>
	</fieldset>

	<button class="fs_continue_btn">
		<a href="index.php"><?= $lang->CONTINUE_BTN ?></a>
	</button>

</main>

<?php require 'html-footer.php'; ?>

<script>
	function save_setting( element ) {
		setCookie( element.target.dataset.name, JSON.stringify(Number(element.target.checked)), 999);
	}

	window.onload = () => {
		setCookie('vege', JSON.stringify(1), 999);
		setCookie('food', JSON.stringify(1), 999);
		setCookie('kela', JSON.stringify(1), 999);
		setCookie('location', JSON.stringify(0), 999);
		setCookie('lang', JSON.stringify('eng'), 999);

		document.getElementById('vegetarian').addEventListener('click', save_setting);
		document.getElementById('cafes').addEventListener('click', save_setting);
		document.getElementById('kela').addEventListener('click', save_setting);
		// document.getElementById('location').addEventListener( 'click', save_setting );
		// document.getElementById('language').addEventListener( 'click', save_setting );
	}
</script>

</body>
</html>
