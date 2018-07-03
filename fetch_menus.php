<?php
error_reporting(E_ERROR);
ini_set('display_errors', "1");

set_include_path(get_include_path().PATH_SEPARATOR.'class/');
spl_autoload_extensions('.class.php');
spl_autoload_register();

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

$db = new DByhteys();

$sql = "SELECT id, json_url FROM restaurants WHERE json_url IS NOT NULL AND food is true";

echo "<a href='settings.php'><i class='material-icons'>settings</i> Back to settings</a>";


foreach ($restaurants as $r) {
    if (empty($r->menuUrl_JSON)) {
        continue;
    }

    $json_string = file_get_contents($r->menuUrl_JSON);

    $menu_json = new MenuJSON( $r, json_decode($json_string));

    file_put_contents("./menus/menu-{$r->id}.json", json_encode($menu_json, 256 | 64));
    file_put_contents("./menus/menu_orig-{$r->id}.json", $json_string);
}
