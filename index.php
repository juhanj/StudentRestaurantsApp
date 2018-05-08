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

if ( empty($_COOKIE['restaurants']) ) {
    /** @var Restaurant[] $restaurants */
    $restaurants = json_decode(file_get_contents('restaurants.json'))->restaurants;

    $temp_array = array();
    foreach ( $restaurants as $r ) {
        $temp_obj = new stdClass();
        $temp_obj->name = $r->name;
        $temp_obj->url = ($r->menuUrl_JSON) ? "./menu?id={$r->id}" : $r->menuUrl;
        $temp_obj->loc = $r->location;
        $temp_obj->addr = $r->address;
        $temp_array[] = $temp_obj;
    }
    $temp_json = json_encode( $temp_array );
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SDSCA</title>
    <link rel="icon" href="stuca.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/main.css">
    <script src="./js/main.js"></script>
    <style>
        .buttons {
            display: flex;
        }

        .buttons a {
            height: 75vh;
            display: flex;
            flex: 1;
            justify-content: center;
            align-items: center;
        }

        .large {
            font-size: 10em;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>SuperDuperStuCaApp</h1>
</div>

<div class="buttons">
    <a href="map.html"><i class="material-icons large">map</i></a>
    <a href="list.php"><i class="material-icons large">menu</i></a>
</div>

<script>
    <?php if ( !empty($temp_json) ) : ?>
        let r = '<?= $temp_json ?>';
        setCookie( "restaurants", r, 7 );
    <?php endif; ?>

    let lat = 62.601262;
    let long = 29.743602;
    setCookie( "location", JSON.stringify([lat, long]), 0 );
</script>

</body>
</html>
