<?php declare(strict_types=1);
require $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/studentrestaurantsapp/components/_start.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang->lang ?>">

<?php require 'html-head.php'; ?>

<body class="grid">

<?php require 'html-header.php'; ?>

<section class="feedback" id="feedback"><?= Utils::check_feedback_POST() ?></section>

<main class="main-body-container">

	<section class="settings box">
		<label>
			<input type="checkbox" id="cafes" name="food"
				<?= $settings->food ? 'checked' : '' ?>
			>
			<span>
				<?= $lang->SETT_FOOD ?><br>
				<?= $lang->SETT_FOOD_INFO ?>
			</span>
		</label>

		<label>
			<input type="checkbox" id="kela" name="kela"
				<?= $settings->kela ? 'checked' : '' ?>
			>
			<span>
				<?= $lang->SETT_KELA ?><br>
				<?= $lang->SETT_KELA_INFO ?>
			</span>
		</label>

		<label>
			<input type="checkbox" id="joensuu" name="joensuu"
				<?= $settings->onlyJoensuu ? 'checked' : '' ?>
			>
			<span>
				<?= $lang->SETT_JOENSUU ?>
			</span>
		</label>
	</section>

	<section class="settings box" id="location">
		<label>
			<input type="checkbox" id="location" name="location"
				<?= $settings->location ? 'checked' : '' ?>
			>
			<span>
				<?= $lang->SETT_LOC ?><br>
				<?= $lang->SETT_LOC_INFO ?>
			</span>
		</label>
	</section>

	<section class="settings box" id="languages">
		<h2 class="settings-head"><?= $lang->SETT_LANG_HEAD ?></h2>
		<p><?= $lang->SETT_LANG_INFO ?></p>

		<label for="english">
			<input type="radio" id="english" name="lang" value="en"
				<?= $settings->lang == 'en' ? 'checked' : '' ?>>
			<span>🇬🇧 English</span>
		</label>

		<label for="finnish">
			<input type="radio" id="finnish" name="lang" value="fi"
				<?= $settings->lang == 'fi' ? 'checked' : '' ?>
			>
			<span>🇫🇮 Suomi</span>
		</label>
	</section>

	<section class="box">
		<a href="https://github.com/juhanj/StudentRestaurantsApp" rel="noopener noreferrer">
			 <?php echo file_get_contents("./img/link.svg"); ?>
			Link to GitHub page
		</a>
	</section>
</main>

<?php require 'html-footer.php'; ?>

<script>
</script>

</body>
</html>
