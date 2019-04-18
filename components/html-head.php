<?php declare(strict_types=1);
$main_css_version = filemtime( './css/main.css' );
$headfoot_css_version = filemtime( './css/header-footer.css' );
$page_css_version = filemtime( './css/' . CURRENT_PAGE . '.css' );
?>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?= $lang->HTML_TITLE ?></title>

	<link rel="icon" href="<?= WEB_PATH ?>/img/favicon-anim.gif" type="image/gif">

	<!-- Material icons CSS -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

	<!-- Modern-Normalize CSS -->
	<link rel="stylesheet" href="<?= WEB_PATH ?>/css/modern-normalize.css">

	<!-- Main CSS file -->
	<link rel="stylesheet" href="<?= WEB_PATH ?>/css/main.css?v=<?=$main_css_version?>">
	<!-- Header-footer CSS file -->
	<link rel="stylesheet" href="<?= WEB_PATH ?>/css/header-footer.css?v=<?=$headfoot_css_version?>">
	<!-- Page specific CSS file -->
	<link rel="stylesheet" href="<?= WEB_PATH ?>/css/<?= CURRENT_PAGE ?>.css?v=<?=$page_css_version?>">

	<!-- Main javascript file -->
	<script defer src="<?= WEB_PATH ?>/js/main.js"></script>
</head>
