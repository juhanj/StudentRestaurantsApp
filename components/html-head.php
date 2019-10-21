<?php declare(strict_types=1);
?>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?= $lang->HTML_TITLE ?></title>

	<link rel="icon" href="<?= WEB_PATH ?>/img/favicon-anim.gif" type="image/gif">

	<!-- Modern-Normalize CSS -->
	<link rel="stylesheet" href="<?= WEB_PATH ?>/css/modern-normalize.css">

	<!-- Main CSS file -->
	<link rel="stylesheet" href="<?= WEB_PATH ?>/css/main.css?v=<?= filemtime( './css/main.css' ) ?>">
	<!-- Header-footer CSS file -->
	<link rel="stylesheet" href="<?= WEB_PATH ?>/css/header-footer.css?v=<?= filemtime( './css/header-footer.css' ) ?>">
	<!-- Page specific CSS file -->
	<link rel="stylesheet" href="<?= WEB_PATH ?>/css/<?= CURRENT_PAGE ?>.css?v=<?= filemtime( './css/' . CURRENT_PAGE . '.css' ) ?>">

	<!-- Main javascript file -->
	<script defer src="<?= WEB_PATH ?>/js/main.js"></script>
	<!-- Page specific javascript file -->
	<script defer src="<?= WEB_PATH ?>/js/<?= CURRENT_PAGE ?>.js"></script>

	<script>
		const WEB_PATH = '<?= WEB_PATH ?>';
	</script>
</head>
