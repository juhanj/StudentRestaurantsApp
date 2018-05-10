<?php
/*============
 * FUNCTIONS *
 ============*/
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
function cmp_dist($a, $b) {
    return $a->distance > $b->distance;
}

/*========
 * LOGIC *
 ========*/
/** @var Restaurant[] $restaurants */
$restaurants = json_decode(file_get_contents('restaurants.json'))->restaurants;

if ( !empty($_COOKIE['location']) ) {
    $loc = json_decode( $_COOKIE['location'] );
    foreach ( $restaurants as $r ) {
        $r->distance = calc_distance( $r->location->lat, $r->location->long, $loc[0], $loc[1])*1000;
    }

    usort($restaurants, "cmp_dist");
}

debug( $restaurants );
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SuperDuperStuCaApp</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="./js/main.js"></script>
</head>
<body>

<script>
</script>

</body>
</html>
