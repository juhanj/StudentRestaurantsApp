<?php
session_start();
$vege = !empty($_COOKIE['vege']) ? $_COOKIE['vege'] : false;
$food = !empty($_COOKIE['food']) ? $_COOKIE['food'] : false;
$kela = !empty($_COOKIE['kela']) ? $_COOKIE['kela'] : false;
$kela = !empty($_COOKIE['location']) ? $_COOKIE['location'] : false;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $lang->HTML_TITLE ?></title>
    <link rel="icon" href="favicon-anim.gif" type="image/gif">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/main.css">
    <script src="./js/main.js"></script>
</head>
<body>

<div class="header">
    <a href="index.php"><i class="material-icons">navigate_before</i></a>
    <h1><?= $lang->HEADER_H1 ?></h1>
</div>

<form>
    <label>
        <input type="checkbox" id="vegetarian" data-name="vege" <?= $vege ? 'checked' : '' ?>>
        <span><?= $lang->SETTING_1 ?></span>
    </label>

    <label>
        <input type="checkbox" id="cafes" data-name="food" <?= $food ? 'checked' : '' ?>>
        <span><?= $lang->SETTING_2 ?></span>
    </label>

    <label>
        <input type="checkbox" id="kela" data-name="kela" <?= $kela ? 'checked' : '' ?>>
        <span><?= $lang->SETTING_3 ?><br><?= $lang->SETTING_3_INFO ?></span>
    </label>
</form>

<form class="settings">
	<fieldset><legend><?= $lang->REST_FIELDSET_LEG ?></legend>
		<label>
			<input type="checkbox" id="vegetarian" data-name="vege" <?= $vege ? 'checked' : '' ?>>
			<span><?= $lang->SETTING_1 ?></span>
		</label>

		<label>
			<input type="checkbox" id="cafes" data-name="food" <?= $food ? 'checked' : '' ?>>
			<span><?= $lang->SETTING_2 ?></span>
		</label>

		<label>
			<input type="checkbox" id="kela" data-name="kela" <?= $kela ? 'checked' : '' ?>>
			<span><?= $lang->SETTING_3 ?><br><?= $lang->SETTING_3_INFO ?></span>
		</label>
	</fieldset>

	<fieldset><legend><?= $lang->LOC_FIELDSET_LEG ?></legend>
		<label>
			<input type="checkbox" id="location" data-name="location" <?= $kela ? 'checked' : '' ?>>
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

<script>
    function save_setting( element ) {
        setCookie( element.target.dataset.name, JSON.stringify(Number(element.target.checked)), 999);
    }

    document.getElementById('vegetarian').addEventListener( 'click', save_setting );
    document.getElementById('cafes').addEventListener( 'click', save_setting );
    document.getElementById('kela').addEventListener( 'click', save_setting );
    document.getElementById('location').addEventListener( 'click', save_setting );
</script>

</body>
</html>
