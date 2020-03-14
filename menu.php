<?php declare(strict_types=1);
require $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/studentrestaurantsapp/components/_start.php';

$id = $_GET['id'];

$json = json_decode( file_get_contents( "restaurants.json", true ) );

$r = Restaurant::buildFromJSON( $json->restaurants->{$id} );

$filename = "./json/menus/{$id}-{$settings->lang}.json";
if ( file_exists( $filename ) ) {
	$file_last_updated = filemtime( $filename );

	if ( date( 'W', $file_last_updated ) === date( 'W' ) ) {
		$menuFile = file_get_contents( $filename, true );

		/** @var MenuJSON $menu */
		$menu = json_decode( $menuFile );
	}
}

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

	<article class="restaurant-info box">
		<h2 class="name"> <?= $r->name ?> </h2>

		<section class="times">
			<?= $r->getHoursHTMLString( $lang ) ?>
		</section>

		<section class="more-info compact">
			<!--
			<p>Address</p>
			<a href="./map.php" class="button">Map link</a>
			-->
			<a href="<?= $r->website_url->{$settings->lang} ?>" class="button url-link" rel="noopener noreferrer">
				<?php echo file_get_contents( "./img/link.svg" ); ?>
				<?= $lang->LINK_TO_SITE ?>
			</a>
		</section>
	</article>

	<article class="box menu-week" hidden>
		<section class="menu-header margins-off">
			<button class="button previous">Prev</button>
			<h2 class="current-day">Current day</h2>
			<button class="button next">Next</button>
		</section>
		<section>Current selected menu</section>
	</article>

	<article class="box menu-week" id="menu-container" <?= isset( $menu ) ? '' : 'hidden' ?>>
		<?php if ( isset( $menu ) ) : ?>
			<?php foreach ( $menu->week as $day ) : ?>
				<?php if ( $day->index <= ($current_day - 1) ) {
					continue;
				} ?>
				<section class="menu-day" data-id="<?= $day->index ?>">
					<h2 class="day-header">
						<?= $lang->{"DAY_{$day->index}_LONG"} ?>,
						<?= $day->lunchHours[0] ?> &ndash; <?= $day->lunchHours[1] ?>
					</h2>
					<?php if ( $day->lunchHours != null or $day->menu != null ) : ?>
						<ul class="menu-list">
							<?php foreach ( $day->menu as $menu ) : ?>
								<li class="food">
									<h3><?= $menu->name ?? '' ?></h3>
									<p class="food-components compact">
										<?php foreach ( $menu->components as $c ) : ?>
											<span><?= $c ?></span>
										<?php endforeach; ?>
										<span class="price"><?= $menu->prices ?></span>
									</p>
								</li>
							<?php endforeach; ?>

							<?php if ( empty( $day->menu ) ) : ?>
								No menu available. (Restaurant may still be open.)
							<?php endif; ?>
						</ul>
					<?php else : ?>
						<p>Restaurant closed.</p>
					<?php endif; ?>
				</section>
				<?= ($day->index < 7) ? '<hr>' : '' ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</article>

	<section class="box menu-loading" id="loading-container" <?= isset( $menu ) ? 'hidden' : '' ?>>
		Please wait, loading menu <span class="loading"></span>
	</section>

	<button class="button" id="force-update">
		<?= $lang->FORCE_UPDATE ?>
	</button>
</main>

<?php require 'html-footer.php'; ?>

<script>
	let restaurantID = '<?= $r->id ?>';
	let language = '<?= $lang->lang ?>';
	let updateNeeded = <?= isset( $menu ) ? 0 : 1 ?>;

	let days = [
		'<?= $lang->DAY_0_LONG ?>',
		'<?= $lang->DAY_1_LONG ?>',
		'<?= $lang->DAY_2_LONG ?>',
		'<?= $lang->DAY_3_LONG ?>',
		'<?= $lang->DAY_4_LONG ?>',
		'<?= $lang->DAY_5_LONG ?>',
		'<?= $lang->DAY_6_LONG ?>',
		'<?= $lang->DAY_7_LONG ?>',
	];
</script>
</body>
</html>
