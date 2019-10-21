<?php declare(strict_types=1);
require $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/studentrestaurantsapp/components/_start.php';

$id = $_GET['id'];

$json = json_decode( file_get_contents( "restaurants.json", true ) );

$r = Restaurant::buildFromJSON( $json->restaurants->{$id} );

$filename = "./json/menus/{$id}-{$settings->lang}.json";
if ( file_exists( $filename ) ) {
	$menuFile = file_get_contents( $filename, true );

	/** @var MenuJSON $menu */
	$menu = json_decode( $menuFile );
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

<section class="feedback" id="feedback"><?= check_feedback_POST() ?></section>

<main class="main-body-container">

	<a href="index.php" class="button return"><?= $lang->RETURN_INDEX ?></a>

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
			<a href="<?= $r->website_url->{$settings->lang} ?>" class="button url-link"
			   target="_blank" rel="noopener noreferrer">
				<?php echo file_get_contents("./img/link.svg"); ?>
				Link to site
			</a>
		</section>
	</article>

	<?php if ( isset( $menu ) ) : ?>
		<article class="box menu-week">
			<!--
			<section class="menu-header margins-off">
				<button class="button previous">Prev</button>
				<h2 class="current-day">Current day</h2>
				<button class="button next">Next</button>
			</section>
			<section>Current selected menu</section>
			-->
			<?php foreach ( $menu->week as $day ) : ?>
				<?php if ( $day->index <= ($current_day-1) ) {
					debug( $current_day);
					continue;
				} ?>
				<section class="menu-day" data-id="<?= $day->index ?>">
					<h2 class="day-header">
						<?= $day->dayname ?>, <?= $day->lunchHours[0] ?> &ndash; <?= $day->lunchHours[1] ?>
					</h2>
					<?php if ( $day->lunchHours != null ) : ?>
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
						Restaurant closed.
					<?php endif; ?>
				</section>
				<hr>
			<?php endforeach; ?>
		</article>
	<?php else : ?>
		<p class="box">
			No menu available.
		</p>
	<?php endif; ?>
</main>

<?php require 'html-footer.php'; ?>
</body>
</html>
