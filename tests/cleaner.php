<?php declare(strict_types=1);
require	$_SERVER['DOCUMENT_ROOT'] . '/mopsi_dev/mymopsi/components/_start.php';
/*/////////////////////////////////////////////////*/

/*
 * Cleaning collections directory
 */

$folders = scandir( INI['Misc']['path_to_collections'] );

Utils::debug( $folders );

foreach ( $folders as $folder ) {
	if ( $folder=='.' or $folder=='..' ) {
		continue;
	}

	$imgs = scandir( INI['Misc']['path_to_collections'] . '/' . $folder );

	Utils::debug( $imgs );

	foreach ( $imgs as $img ) {
		if ( $img=='.' or $img=='..' ) continue;

		unlink( INI['Misc']['path_to_collections'] . '/' . $folder . '/' . $img );
	}

	Utils::debug( rmdir( INI['Misc']['path_to_collections'] . '/' . $folder ) );
}

/*
 * Dropping tables, and recreating them with testdata
 */

$sql = "drop table if exists mymopsi_img, mymopsi_collection, mymopsi_user";
$db->query(
	$sql
);

setcookie('collections', '', -1, '/mopsi_dev/mymopsi');

echo "<a href='../db/install.php'>Link to install</a>";
