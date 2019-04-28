<?php declare(strict_types=1);
require __DIR__ . '/components/_start.php';

function print_hour ( $lunchHour , $normalLunchHour ) {
	$style = ($lunchHour != $normalLunchHour) ? 'style="color: firebrick;"' : '';

	return "<span $style>$lunchHour</span>";
}

$id = $_GET[ 'id' ];

$json = json_decode(
	file_get_contents( "restaurants.json", true )
)->restaurants[$id];
$restaurant = new Restaurant( $json );

/** @var \MenuJSON $menuJSON */
$menuJSON = json_decode(
	file_get_contents( "menus/menu-{$id}-{$lang->lang}.json" , true )
);

$menu_available = (bool)$menuJSON;
?>
<!DOCTYPE html>
<html lang="<?= $lang->lang ?>">
<?php require 'html-head.php'; ?>

<body class="grid">

<?php require 'html-header.php'; ?>

<main class="main-body-container">

	<div hidden>
		<?php debug($menuJSON); ?>
	</div>

	<a href="index.php" class="button return"><?= $lang->RETURN_INDEX ?></a>

	<?php if ( $menu_available ) : ?>
		<?php foreach ( $menuJSON->week as $day ) : ?>
			<hr>
			<?php if ( false and $day->index <= date('N') ) { continue; } ?>
			<figure data-id="<?= $day->index ?>">
				<figcaption style="font-weight: bold; font-size: larger;">
					<?= $day->dayname ?>, <?= $day->lunchHours[0] ?> &ndash; <?= $day->lunchHours[0] ?>
				</figcaption>
				<?php if ( $day->lunchHours != null ) : ?>
					<ul>
						<?php foreach ( $day->menu as $menu ) : ?>
							<li>
								<span style="font-weight: bold;"><?= $menu->name ?></span><br>
								<?php foreach ( $menu->components as $c ) : ?>
									<?= $c ?><br>
								<?php endforeach; ?>
							</li>
						<?php endforeach; ?>

						<?php if ( empty( $day->menu ) ) : ?>
							No menu available. (Restaurant may still be open.)
						<?php endif; ?>
					</ul>
				<?php else : ?>
					Restaurant closed.
				<?php endif; ?>
			</figure>
		<?php endforeach; ?>
	<?php else : ?>
		No menu available.
	<?php endif; ?>
</main>
</body>
</html>
