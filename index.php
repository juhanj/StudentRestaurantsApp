<?php declare(strict_types=1);
require $_SERVER['DOCUMENT_ROOT'] . '/superduperstucaapp/components/_start.php';

function fetch_restaurants( DBConnection $db, $location, string $language ) {
	$sql = "select id, name, latitude, longitude, food, kela, address, city,
				m.url as website_url, m.json_url as json_url
			from restaurant r
			join menuurls m
				on r.id = m.restaurant_id
				and ? = m.language";
	$values = [$language];

	if ( $location ) {
		$sql = "select id, name, latitude, longitude, food, kela, address, city,
					m.url as website_url, m.json_url as json_url,
					geodistance( r.latitude, r.longitude, ?, ? ) as distance
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
?>
<!DOCTYPE html>
<html lang="<?= $lang->lang ?>">

<?php require 'html-head.php'; ?>

<body class="grid">

<?php require 'html-header.php'; ?>

<main class="main-body-container">
	<ol class="restaurant-list">
		<?php foreach ( $restaurants as $r ) : ?>
			<?php if ( (!$food or $r->food) and (!$kela or $r->kela) ) : ?>

			<li class="list-item restaurant-list-grid margins-off">
				<details class="restaurant-details">
					<summary>
						<h2 class="list-item-head">
							<?= $r->name ?>
							<span class="restaurant-distance"><?= $r->printDistance() ?></span>
						</h2>
					</summary>

					<div class="more-info">
						<p class="address"><?= $r->address ?></p>
						<span><?= $lang->R_LIST_HOURS_HEAD ?>:</span>
						<ol class="opening-hours-list compact">
							<?php $i = 0;
							foreach ( $r->normalLunchHours as $hours ) : ?>
								<li>
									<p class="opening-hours">
										<span class="day-name"><?= $day_names[ $i++ ] ?></span>
										<span class="opening-hours">
											<?= $r->printHours( $hours , $lang ) ?></span>
									</p>
								</li>
							<?php endforeach; ?>
						</ol>
					</div>
				</details>

				<div class="quick-menu">
					<?php if ( false ) : ?>
						<ul class="day-menu">
							<li class="menu-item">
							</li>
						</ul>
					<?php elseif ( true ) : ?>
						<ul class="day-menu">
							<li class="menu-item">
								<span style="font-weight: bold;">Kasvislounas</span><br>
								Feta-pinaattipihvejä<br>
								Tzatsikia<br>
								Keitettyjä perunoita
							</li>
							<li class="menu-item">
								<span style="font-weight: bold;">Kasviskeitto</span><br>
								Tomaattikeittoa ja fetajuustoa
							</li>
							<li class="menu-item">
								<span style="font-weight: bold;">Lounas</span><br>
								Jauhelihalasagnettea
							</li>
							<li class="menu-item">
								<span style="font-weight: bold;">Salaattilounas</span><br>
								Paahtopaistisalaatti
							</li>
							<li class="menu-item">
								<span style="font-weight: bold;">Annosruoka</span><br>
								Maissipaneroitua broileria<br>
								Chilikermaviilikastiketta<br>
								Pähkinämaustettua paistettua riisiä
							</li>
						</ul>
					<?php else : ?>
						<p>No menu available. (YET!)</p>
					<?php endif; ?>
				</div>

				<div class="links">
					<a href="map.php?id=<?= $r->id ?>" class="button">
						<i class="material-icons">directions</i>
					</a>
					<?= $r->printMenuLink() ?>
				</div>
			</li>

			<?php endif; ?>
		<?php endforeach; ?>
	</ol>
</main>

<?php require 'html-footer.php'; ?>

</body>
</html>
