<?php
require './class/dbyhteys.class.php';
require './class/language.class.php';

session_start();

if ( false and !empty($_COOKIE['food']) ) {
	header( 'Location: index.php' );
	exit;
}

$db = new DByhteys();
$lang = new Language( $db );
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
	<h1><?= $lang->HEADER_H1 ?></h1>
</div>

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
			<input type="checkbox" id="location" data-name="location">
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

<script>
	function save_setting( element ) {
		setCookie( element.target.dataset.name, JSON.stringify(Number(element.target.checked)), 999);
	}

	setCookie( 'vege', JSON.stringify(1), 999 );
	setCookie( 'food', JSON.stringify(1), 999 );
	setCookie( 'kela', JSON.stringify(1), 999 );
	setCookie( 'location', JSON.stringify(0), 999 );

	document.getElementById('vegetarian').addEventListener( 'click', save_setting );
	document.getElementById('cafes').addEventListener( 'click', save_setting );
	document.getElementById('kela').addEventListener( 'click', save_setting );
	document.getElementById('location').addEventListener( 'click', save_setting );
</script>

</body>
</html>
