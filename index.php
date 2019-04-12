<?php declare(strict_types=1);
require $_SERVER['DOCUMENT_ROOT'] . '/superduperstucaapp/components/_start.php';

function fetch_restaurants( DBConnection $db, $location, string $language, Settings $settings ) {
	$sql = "select id, name, latitude, longitude, food, kela, address, city,
				m.website_url as website_url, m.json_url as json_url
			from restaurant r
			join menuurls m
				on r.id = m.restaurant_id
				and ? = m.language";
	$values = [$language];

	if ( $location ) {
		$sql = "select id, name, latitude, longitude, food, kela, address, city,
					m.website_url as website_url, m.json_url as json_url,
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

		if ( $settings->hasMenusBeenUpdatedThisWeek() ) {
			$r->fetchQuickMenu( $language );
		}
	}

	return $restaurants;
}

$day_names = [
	$lang->R_LIST_HOURS_1 , $lang->R_LIST_HOURS_2 , $lang->R_LIST_HOURS_3 , $lang->R_LIST_HOURS_4 ,
	$lang->R_LIST_HOURS_5 , $lang->R_LIST_HOURS_6 , $lang->R_LIST_HOURS_7
];

$restaurants = fetch_restaurants( $db, $location, $lang->lang, $settings );
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
						<a href="<?= $r->website_url ?>">Link to website</a>
					</div>
				</details>

				<?php if ( !empty($r->quickMenu->menu) ) : ?>
					<details class="quick-menu">
						<summary>Quick menu</summary>
						<?= $r->prettyPrintQuickMenu() ?>
					</details>
				<?php endif; ?>

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
