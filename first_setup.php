<?php declare(strict_types=1);
require $_SERVER[ 'DOCUMENT_ROOT' ] . '/superduperstucaapp/components/_start.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang->lang ?>">

<?php require 'html-head.php'; ?>

<body class="grid">

<?php require 'html-header.php'; ?>

<main class="main-body-container">

	<div class="settings">
		<label>
			<input type="checkbox" id="vegetarian" data-name="vege" checked>
			<span>
				<?= $lang->SETTING_1 ?><br>
				<?= $lang->SETTING_1_INFO ?>
			</span>
		</label>

		<label>
			<input type="checkbox" id="cafes" data-name="food" checked>
			<span>
				<?= $lang->SETTING_2 ?><br>
				<?= $lang->SETTING_2_INFO ?>
			</span>
		</label>

		<label>
			<input type="checkbox" id="kela" data-name="kela" checked>
			<span>
				<?= $lang->SETTING_3 ?><br>
				<?= $lang->SETTING_3_INFO ?>
			</span>
		</label>
	</div>

	<div class="settings">
		<label>
			<input type="checkbox" id="location" data-name="location" disabled>
			<span>
				<?= $lang->SETTING_4 ?><br>
				<?= $lang->SETTING_4_INFO ?>
			</span>
		</label>
	</div>

	<div class="settings">
		<p>
			<a href="fetch_menus.php">
				<span>
					<?= $lang->SETTING_DB_UPDATE ?>
					<i class="material-icons" style="margin-bottom: 0;">refresh</i>
				</span><br>
				<span>This might take a few seconds.</span>
			</a>
		</p>
	</div>

	<a href="index.php" class="button">
		<span><?= $lang->CONTINUE_BTN ?></span>
	</a>

</main>

<?php require 'html-footer.php'; ?>

<script>
	function save_setting(element) {
		setCookie(element.target.dataset.name, JSON.stringify(Number(element.target.checked)), 999);
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
