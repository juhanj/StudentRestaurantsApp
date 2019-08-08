<?php declare(strict_types=1);
require __DIR__ . '/components/_start.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang->lang ?>">

<?php require 'html-head.php'; ?>

<body class="grid">

<?php require 'html-header.php'; ?>

<div class="feedback" id="feedback"><?= check_feedback_POST() ?></div>

<main class="main-body-container">

	<a href="index.php" class="button return"><?= $lang->RETURN_INDEX ?></a>

	<div class="settings">
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
			<input type="radio" id="english" name="lang" value="en"
				<?= $settings->lang == 'en' ? 'checked' : '' ?>>
			<?= $lang->SETT_LANG_ENG ?>
		</label>

		<label for="finnish">
			<input type="radio" id="finnish" name="lang" value="fi"
				<?= $settings->lang == 'fi' ? 'checked' : '' ?>>
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
</script>

</body>
</html>
