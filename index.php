<?php declare(strict_types=1);
require '_start.php';

function fetch_restaurants( DByhteys $db, $location, string $language ) {
	$sql = "select id, name, json_url, latitude, longitude, food, kela, address, city, 
				m.url as website_url
			from restaurant r
			join menuurls m 
				on r.id = m.restaurant_id
				and ? = m.language";
	$values = [$language];

	if ( $location ) {
		$sql = "select id, name, json_url, latitude, longitude, food, kela, address, city,
						geodistance( r.latitude, r.longitude, ?, ? ) as distance, 
						m.url as website_url
			        from restaurant r
					join menuurls m 
						on r.id = m.restaurant_id
						and ? = m.language
			        order by distance";
		$values = [ $location[ 0 ] , $location[ 1 ], $language ];
	}

	/** @var \Restaurant[] $restaurants */
	$restaurants = $db->query( $sql , $values , FETCH_ALL, 'Restaurant' );

	foreach ( $restaurants as $r ) {
		$r->fetchNormalLunchHours( $db );
		$_SESSION[ 'times' ][ $r->id ] = $r->normalLunchHours;
	}

	return $restaurants;
}

$day_names = [
	$lang->R_LIST_HOURS_1 , $lang->R_LIST_HOURS_2 , $lang->R_LIST_HOURS_3 , $lang->R_LIST_HOURS_4 ,
	$lang->R_LIST_HOURS_5 , $lang->R_LIST_HOURS_6 , $lang->R_LIST_HOURS_7
];

$restaurants = fetch_restaurants( $db, $location, $lang->lang );

print_r( $restaurants );
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?= $lang->HTML_TITLE ?></title>
	<link rel="icon" href="favicon-anim.gif" type="image/gif">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="css/main.css">
</head>
<body>

<header class="header">
	<a href="map.php"><i class="material-icons">map</i></a>
	<h1><?= $lang->HEADER_H1 ?></h1>
	<a href="settings.php"><i class="material-icons">settings</i></a>
</header>

<main>
	<ol class="restaurant-list">
		<?php foreach ( $restaurants as $r ) : ?>

			<?php if ( !$food OR $r->food ) : ?>
				<?php if ( !$kela OR $r->kela ) : ?>

					<li class="list-item">
						<details>
							<summary>
								<h2 class="list-head">
									<?= $r->name ?>
									<span class="restaurant-distance"><?= $r->print_distance() ?></span>
								</h2>
								<br>
							</summary>

							<div class="more-info">
								<p><?= $r->address ?></p>
								<span><?= $lang->R_LIST_HOURS_HEAD ?>:</span>
								<ol class="opening-hours-list">
									<?php $i = 0;
									foreach ( $r->normalLunchHours as $hours ) : ?>
										<li>
											<span class="day-name"><?= $day_names[ $i++ ] ?></span>
											<span class="opening-hours"><?= $r->print_hours( $hours , $lang ) ?></span>
										</li>
									<?php endforeach; ?>
								</ol>
							</div>
						</details>

						<div class="buttons">
							<a href="map.php?id=<?= $r->id ?>">
								<i class="material-icons">directions</i>
							</a>
							<?= $r->print_menu_link() ?>
						</div>
					</li>

				<?php endif; ?>
			<?php endif; ?>

		<?php endforeach; ?>
	</ol>
</main>

</body>
</html>
