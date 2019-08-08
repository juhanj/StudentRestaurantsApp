<?php declare(strict_types=1);
?>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?= $lang->HTML_TITLE ?></title>

	<link rel="icon" href="./img/favicon-anim.gif" type="image/gif">

	<!-- Material icons CSS -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

	<!-- Modern-Normalize CSS -->
	<link rel="stylesheet" href="./css/modern-normalize.css">

	<!-- Main CSS file -->
	<link rel="stylesheet" href="./css/main.css?v=<?= filemtime( './css/main.css' ) ?>">
	<!-- Header-footer CSS file -->
	<link rel="stylesheet" href="./css/header-footer.css?v=<?= filemtime( './css/header-footer.css' ) ?>">
	<!-- Page specific CSS file -->
	<link rel="stylesheet" href="./css/<?= CURRENT_PAGE ?>.css?v=<?= filemtime( './css/' . CURRENT_PAGE . '.css' ) ?>">

	<!-- Main javascript file -->
	<script defer src="./js/main.js"></script>
	<!-- Page specific javascript file -->
	<script defer src="./js/<?= CURRENT_PAGE ?>.js"></script>

	<script>
		const WEB_PATH = '<?= WEB_PATH ?>';
	</script>
</head>
