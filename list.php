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

function calc_distance( $lat1, $lon1, $lat2, $lon2) {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);

    return $dist * 60 * 1.1515 * 1.609344;
}

function print_distance( $dist ) {
    if ( !$dist ) { return ''; }

    $unit = "m";
    $decimals = 0;

    if ( $dist > 1500 ) {
        $dist = $dist / 1000;
        $unit = "km";
        $decimals = 1;
    }
    $dist = number_format( $dist, $decimals, ",", ".");

    return "&mdash; (~{$dist} {$unit})";
}

function cmp_dist($a, $b) {
    return $a->distance > $b->distance;
}

function print_menu_link( $r ) {
    if ( !empty($r->menuUrl_JSON) ) {
        return "<a href='menu.php?id={$r->id}'><i class='material-icons'>restaurant_menu</i></a>";
    }
    else {
        return "<a href='{$r->menuUrl}'><i class='material-icons'>link</i></a>";
    }
}

/** @var Restaurant[] $restaurants */
$restaurants = json_decode(file_get_contents('restaurants.json'))->restaurants;

$date = new DateTime('now');
$_SESSION['current_day'] = $date->format('N')-1;

foreach ( $restaurants as $r ) {
    $_SESSION['times'][$r->id] = $r->normalLunchHours;
}

if ( !empty($_COOKIE['location']) ) {
    $loc = json_decode( $_COOKIE['location'] );
    foreach ( $restaurants as $r ) {
        $r->distance = calc_distance( $r->location->lat, $r->location->long, $loc[0], $loc[1])*1000;
    }

    usort($restaurants, "cmp_dist");
}
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
            padding-left: 1em;
            padding-top: 0.9em;
            margin: 0;
            font-size: xx-large;
        }


        li:nth-child(even) .buttons { background: gainsboro; }
        li:nth-child(odd) .buttons  { background: #d7eaff; }

        li p {
            margin: 10px;
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
            font-size: 5em;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="map.html"><i class="material-icons">map</i></a>
    <h1>Restaurants</h1>
    <a href="settings.php"><i class="material-icons">settings</i></a>
</div>



<ol class="css-magic">
    <?php foreach ( $restaurants as $r ) : ?>
        <li data-id="<?=$r->id?>">
            <h2><?= $r->name ?> <span id="dist-<?=$r->id?>"><?= print_distance($r->distance) ?></span></h2>
            <p><?= $r->address ?></p>
            <div class="buttons">
                <a href="map.html?id=<?=$r->id?>"><i class="material-icons">directions</i></a>
                <?= print_menu_link($r) ?>
            </div>
        </li>
    <?php endforeach; ?>
</ol>

<script>
</script>

</body>
</html>
