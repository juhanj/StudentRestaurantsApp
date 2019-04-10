<?php declare(strict_types=1);
require $_SERVER['DOCUMENT_ROOT'] . '/superduperstucaapp/components/_start.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang->lang ?>">

<?php require 'html-head.php'; ?>

<body class="grid">

<?php require 'html-header.php'; ?>

<main class="main-body-container">

	<div class="settings">
		<label>
			<input type="checkbox" id="vegetarian" data-name="vege"
			       <?= $vege ? 'checked' : '' ?>>
			<span>
				<?= $lang->SETTING_1 ?><br>
				<?= $lang->SETTING_1_INFO ?>
			</span>
		</label>

		<label>
			<input type="checkbox" id="cafes" data-name="food"
				<?= $food ? 'checked' : '' ?>>
			<span>
				<?= $lang->SETTING_2 ?><br>
				<?= $lang->SETTING_2_INFO ?>
			</span>
		</label>

		<label>
			<input type="checkbox" id="kela" data-name="kela"
				<?= $kela ? 'checked' : '' ?>>
			<span>
				<?= $lang->SETTING_3 ?><br>
				<?= $lang->SETTING_3_INFO ?>
			</span>
		</label>
	</div>

	<div class="settings">
		<label>
			<input type="checkbox" id="location" data-name="location"
				<?= (bool)$location ? 'checked' : '' ?>>
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
					<i class="material-icons">refresh</i>
				</span><br>
				<span><?= $lang->SETTING_DB_UPDATE ?></span>
			</a>
		</p>
	</div>

</main>

<?php require 'html-footer.php'; ?>

<script>
    function save_setting(element) {
	    setCookie(element.target.dataset.name, JSON.stringify(Number(element.target.checked)), 999);
    }

    /**
     * Need setCookie from main.js file (in header, deferred)
     */
    window.onload = () => {
    	// Loop through all input[type=checkbox] elements
	    // So easy, gotta love not having to support IE
    	document.querySelectorAll("input[type=checkbox]").forEach( (input) => {
    		// Save cookie from currently set value
		    setCookie( input.dataset.name, JSON.stringify(input.checked), 999 );
		    // Add a listener for user made changes
		    input.addEventListener('click', save_setting);
	    });
    }
</script>

</body>
</html>
