<?php
session_start();

function debug($var, $var_dump = false)
{
    echo "<br>\r\n<pre>Print_r ::<br>\r\n";
    print_r($var);
    echo "</pre>";
    if ($var_dump) {
        echo "<br><pre>Var_dump ::<br>\r\n";
        var_dump($var);
        echo "</pre><br>\r\n";
    };
}

function print_hour( $lunchHour, $normalLunchHour ) {
    $style = ( $lunchHour != $normalLunchHour ) ? 'style="color: firebrick;"' : '';

    return "<span $style>$lunchHour</span>";
}

$id = $_GET['id'];

/** @var MenuJSON $restaurant */
$restaurant = json_decode(file_get_contents("./menus/menu-{$id}.json"));
if ( $restaurant ) {
    $week = $restaurant->week;
}
$menu_available = (bool)$restaurant;
$vege = !empty($_COOKIE['vege']) ? $_COOKIE['vege'] : false;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SDSCA - Menu</title>
    <link rel="icon" href="favicon-anim.gif" type="image/gif">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/main.css">
    <style>

    </style>
</head>
<body>

<div class="header">
    <a href="list.php"><i class="material-icons">navigate_before</i></a>
    <h1>Menu</h1>
    <a href="settings.php"><i class="material-icons">settings</i></a>
</div>

<p>Normal prices: student 1,88 € / staff 4,10 € / guest 7,20 €</p>

<?php if ( $menu_available ) : ?>
    <?php foreach ($week as $day) : ?>
        <?php if ( $day->index >= $_SESSION['current_day'] ) : ?>
        <figure data-id="<?= $day->index ?>" style="margin-top: 5em;
                <?= ($day->index < $_SESSION['current_day']) ? 'display: hidden;' : ''; ?>">
            <figcaption style="font-weight: bold; font-size: larger;"><?=$day->dayName?>,
                <?= print_hour( $day->lunchHours[0], $_SESSION['times'][$restaurant->id][$day->index][0] ) ?>
                &ndash;
                <?= print_hour( $day->lunchHours[1], $_SESSION['times'][$restaurant->id][$day->index][1] ) ?>
            </figcaption>

            <?php if ($day->lunchHours != null) : ?>
                <ul>
                    <?php foreach ($day->menu as $menu) : ?>
                        <?php if ( !$vege OR $menu->vegetarian ) : ?>
                            <li>
                                <span style="font-weight: bold;"><?= $menu->name ?></span><br>
                                <?php if ( $menu->prices != "Opiskelija 1,88 € / Henkilökunta 4,10 € / Vierailija 7,20 €" ) : ?>
                                    <?= $menu->prices ?><br>
                                <?php endif; ?>
                                <?php foreach ($menu->components as $c) : ?>
                                    <?= $c ?><br>
                                <?php endforeach; ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ( empty($day->menu) ) : ?>
                        No menu available. (Restaurant may still be open.)
                    <?php endif; ?>
                </ul>
            <?php else : ?>
                Restaurant closed.
            <?php endif; ?>
        </figure>
        <?php endif; ?>
    <?php endforeach; ?>

<?php else : ?>
    No menu available.
<?php endif; ?>

</body>
</html>
