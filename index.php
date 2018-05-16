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

$date = new DateTime('now');
$_SESSION['current_day'] = $date->format('N')-1;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SDSCA</title>
    <link rel="icon" href="favicon-anim.gif" type="image/gif">
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
    <a href="map.php"><i class="material-icons large">map</i></a>
    <a href="list.php"><i class="material-icons large">menu</i></a>
</div>

<script>
    getLocation();
</script>

</body>
</html>
