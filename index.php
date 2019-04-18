<?php declare(strict_types=1);
require __DIR__ . '/components/_start.php';

/**
 * @param \Settings $settings Used for checking if the menu has been updated this week, for quick-menu.
 * @param \Language $language Quick-menu language
 * @return \Restaurant[]
 */
function fetch_restaurants( Settings $settings, Language $language ) {

	$json = json_decode(
		file_get_contents( "restaurants.json", true )
	);

	$restaurants = [];
	foreach ( $json->restaurants as $obj ) {
		$temp_var =  new Restaurant( $obj );

		if ( $settings->haveMenusBeenUpdatedThisWeek() ) {
			$temp_var->fetchQuickMenu( $language->lang );
		}

		$restaurants[] = $temp_var;
	}

	return $restaurants;
}

$day_names = [
	$lang->R_LIST_HOURS_1 , $lang->R_LIST_HOURS_2 , $lang->R_LIST_HOURS_3 , $lang->R_LIST_HOURS_4 ,
	$lang->R_LIST_HOURS_5 , $lang->R_LIST_HOURS_6 , $lang->R_LIST_HOURS_7
];

$restaurants = fetch_restaurants( $settings, $lang );
?>
<!DOCTYPE html>
<html lang="<?= $lang->lang ?>">

<?php require 'html-head.php'; ?>

<body class="grid">

<?php require 'html-header.php'; ?>

<main class="main-body-container">
	<ol class="restaurant-list">
		<?php foreach ( $restaurants as $r ) : ?>
			<?php
			if ( ($settings->food and !$r->food) ) { continue; }
			if ( ($settings->kela and !$r->kela) ) { continue; }
			?>

			<li class="list-item restaurant-list-grid margins-off" data-id="<?= $r->id ?>">
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
							<?php foreach ( $r->normalLunchHours as $index => $hours ) : ?>
								<li>
									<p class="opening-hours">
										<span class="day-name"><?= $day_names[ $index ] ?></span>
										<span class="opening-hours">
											<?= $r->printHours( $index , $lang ) ?>
										</span>
									</p>
								</li>
							<?php endforeach; ?>
						</ol>
						<p>
							<a href="map.php?id=<?= $r->id ?>" class="button">
								<?= $lang->R_LIST_DIRECTIONS ?>
								<i class="material-icons">directions</i>
							</a>
						</p>
					</div>
				</details>

				<?php if ( !empty($r->quickMenu) ) : ?>
					<details class="quick-menu">
						<summary>Quick menu</summary>
						<?= $r->prettyPrintQuickMenu() ?>
					</details>
				<?php endif; ?>

				<div class="links">
					<?= $r->printListLinks( $lang ) ?>
				</div>
			</li>

		<?php endforeach; ?>
	</ol>
</main>

<?php require 'html-footer.php'; ?>

</body>
</html>
