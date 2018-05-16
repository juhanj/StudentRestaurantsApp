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

function calc_distance($lat1, $lon1, $lat2, $lon2)
{
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);

    return $dist * 60 * 1.1515 * 1.609344;
}

function print_distance($dist)
{
    if (!$dist) {
        return '';
    }

    $unit = "m";
    $decimals = 0;

    if ($dist > 1500) {
        $dist = $dist / 1000;
        $unit = "km";
        $decimals = 1;
    }
    $dist = number_format($dist, $decimals, ",", ".");

    return "(~{$dist} {$unit})";
}

function cmp_dist($a, $b)
{
    return $a->distance > $b->distance;
}

function print_menu_link($r)
{
    if (!empty($r->menuUrl_JSON)) {
        return "<a href='menu.php?id={$r->id}'><i class='material-icons'>restaurant_menu</i></a>";
    } else {
        return "<a href='{$r->menuUrl}'><i class='material-icons'>link</i></a>";
    }
}

function print_hours($hours)
{
    if (empty($hours)) {
        return "<i class='material-icons' style='color: firebrick;'>close</i>Closed";
    }
    return "{$hours[0]} &ndash; {$hours[1]}";
}

/** @var Restaurant[] $restaurants */
$restaurants = json_decode(file_get_contents('restaurants.json'))->restaurants;

foreach ($restaurants as $r) {
    $_SESSION['times'][$r->id] = $r->normalLunchHours;
}

if (!empty($_COOKIE['location'])) {
    $loc = json_decode($_COOKIE['location']);
    foreach ($restaurants as $r) {
        $r->distance = calc_distance($r->location->lat, $r->location->long, $loc[0], $loc[1]) * 1000;
    }

    usort($restaurants, "cmp_dist");
}
$day_names = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

$food = !empty($_COOKIE['food']) ? $_COOKIE['food'] : false;
$kela = !empty($_COOKIE['kela']) ? $_COOKIE['kela'] : false;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SDSCA - List</title>
    <link rel="icon" href="favicon-anim.gif" type="image/gif">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/main.css">
    <style>
        ol {
            list-style: none;
            padding: 0;
        }

        li h2 {
            display: inline;
            font-size: 1.5em;
        }

        li h2 span {
            padding-left: 1em;
        }

        li .buttons {
            background: #d7eaff;
        }

        summary {
            margin: 50px 0;
        }

        details div {
            margin-left: 0.5em;
        }

        .buttons {
            display: flex;
        }

        .buttons a {
            display: flex;
            flex: 1;
            justify-content: center;
            align-items: center;
            border: 1px solid black;
        }

        .buttons a .material-icons {
            font-size: 4em;
        }

        .day-name {
            display: inline-grid;
            width: 3em;
        }

        ol.opening-hours-list {
            margin-left: 1em;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="map.php"><i class="material-icons">map</i></a>
    <h1>Restaurants</h1>
    <a href="settings.php"><i class="material-icons">settings</i></a>
</div>

<ol>
    <?php foreach ($restaurants as $r) : ?>

        <?php if ( !$food OR $r->food ) : ?>
            <?php if ( !$kela OR $r->kela ) : ?>
                <li data-id="<?= $r->id ?>">

                    <details>
                        <summary>
                            <h2><?= $r->name ?> <span id="dist-<?= $r->id ?>"><?= print_distance($r->distance) ?></span>
                            </h2><br>
                        </summary>
                        <div>
                            <p><?= $r->address ?></p>
                            <span>Normal opening hours:</span>
                            <ol class="opening-hours-list">
                                <?php $i = 0;
                                foreach ($r->normalLunchHours as $hours) : ?>
                                    <li>
                                        <span class="day-name"><?= $day_names[$i++] ?></span>
                                        <span class="opening-hours"><?= print_hours($hours) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        </div>
                    </details>


                    <div class="buttons">
                        <a href="map.php?id=<?= $r->id ?>"><i class="material-icons">directions</i></a>
                        <?= print_menu_link($r) ?>
                    </div>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</ol>

<script>
</script>

</body>
</html>
