<?php
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
debug( $_COOKIE );

debug( file_get_contents('restaurants.json') );
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
    let lat = 62.601262;
    let long = 29.743602;
    setCookie("location",JSON.stringify([lat, long]),0);
</script>

</body>
</html>
