<?php declare(strict_types=1);
require $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/studentrestaurantsapp/components/_start.php';

/**
 * @param Settings $sett
 * @param bool $sort
 * @return Restaurant[]
 */
function fetch_restaurants ( Settings $sett, bool $sort = false ) {
	$json = json_decode(
		file_get_contents( "restaurants.json", true )
	);

	$restaurants = [];
	foreach ( $json->restaurants as $obj ) {
		$rest = Restaurant::buildFromJSON( $obj );

		if ( $sett->location ) {
			$rest->calcDistance( $sett->location );
		}
		$restaurants[] = $rest;
	}

	if ( $sort ) {
		usort( $restaurants, function ( $a, $b ) { return strcmp( $a->name, $b->name ); } );
	}
	return $restaurants;
}

$restaurants = fetch_restaurants( $settings );
$current_day = (int)date( 'N' );
$next_day = ($current_day === 7)
	? 1
	: $current_day + 1;

?>

<!DOCTYPE html>
<html lang="<?= $settings->lang ?>">

<?php require 'html-head.php'; ?>

<body class="grid">

<?php require 'html-header.php'; ?>

<section class="feedback" id="feedback"><?= Utils::check_feedback_POST() ?></section>

<main class="main-body-container">
	<ol class="restaurant-list">
		<?php foreach ( $restaurants as $r ) : ?>
			<?php
			if ( $settings->food and !$r->food ) {
				continue;
			}
			if ( $settings->kela and !$r->kela ) {
				continue;
			}
			if ( $settings->onlyJoensuu and $r->city != 'Joensuu' ) {
				continue;
			}
			?>

			<li class="box restaurant compact <?= ($r->isOpenRightNow()) ? 'open' : 'closed' ?>"
			    data-id="<?= $r->id ?>">
				<h2 class="name"> <?= $r->name ?> </h2>

				<p class="lunch-time current-open margins-off">
					<span><?= $lang->TIMES ?>:</span>
					<span><?= $r->getHoursToday( $lang ) ?></span>
				</p>

				<p class="lunch-time tomorrow-open margins-off">
					<span><?= $lang->{"DAY_{$next_day}_LONG"} ?>:</span>
					<span><?= $r->getHoursDay( $next_day - 1, $lang ) ?></span>
				</p>

				<p class="other-info margins-off">
					<span><?= $lang->FOOD ?>: <?= ($r->food) ? '✔' : '❌' ?></span>
					|
					<span><?= $lang->KELA ?>: <?= ($r->kela) ? '✔' : '❌' ?></span>
				</p>

				<?php if ( $settings->location ) : ?>
					<p class="restaurant-distance"><?= $r->distance ?>&nbsp;m</p>
				<?php endif; ?>

				<a href="menu.php?id=<?= $r->id ?>" class="button"><?= $lang->LINK_MENU ?></a>
			</li>

		<?php endforeach; ?>
	</ol>
</main>

<?php require 'html-footer.php'; ?>

</body>
</html>
