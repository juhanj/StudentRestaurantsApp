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

echo "<a href='settings.php'><i class='material-icons'>settings</i> Back to settings</a>";

if ( !file_exists("./menus/") OR !is_readable("./menus/") OR !is_writable("./menus/") ) {
    echo "<p>Could not access necessary folder to save database.</p>";
    exit();
}

/**
 * @var Restaurant $restaurants
 */
$restaurants = json_decode(file_get_contents('restaurants.json'))->restaurants;

foreach ($restaurants as $r) {
    if (empty($r->menuUrl_JSON)) {
        continue;
    }

    $json_string = file_get_contents($r->menuUrl_JSON);

    $menu_json = new MenuJSON( $r, json_decode($json_string));


/*
    $menu_json = json_decode($json_string);

    $menu_json->id = $r->id;
    $menu_json->name = $menu_json->RestaurantName;
    $menu_json->url = $menu_json->RestaurantUrl;
    $menu_json->week = $menu_json->MenusForDays;

    foreach ($menu_json->week as $day) {
        $day->index;
        $day->date = $day->Date;
        $properDate = DateTime::createFromFormat('Y-m-d\TH:i:sT', $day->date);
        $day->index = $properDate->format('N')-1;
        $day->dayName = $properDate->format('l');
        $day->lunchHours = explode("-", $day->LunchTime);

        if (count($day->lunchHours) != 2 AND count($day->lunchHours[0]) != 5 AND count($day->lunchHours[1]) != 5) {
            unset($day->lunchHours);
            if ( !empty( $day->SetMenus ) ) {
                $day->lunchHours = $r->normalLunchHours[$day->index];
            } else {
                $day->lunchHours = null;
            }
        } else {
            foreach ($day->lunchHours as $t) {
                $t = DateTime::createFromFormat("H.i", $t)->format("H:i");
            }
        }

        $day->menu = $day->SetMenus;

        foreach ($day->menu as $food) {
            $food->name = $food->Name;
            $food->prices = $food->Price;
            $food->vegetarian = ($food->name == "Kasvislounas" OR $food->name == "Kasviskeitto");
            $food->components = $food->Components;


            unset($food->SortOrder);
            unset($food->Name);
            unset($food->Price);
            unset($food->Components);
        }
        unset($day->Date);
        unset($day->LunchTime);
        unset($day->SetMenus);
    }
    unset($menu_json->RestaurantName);
    unset($menu_json->RestaurantUrl);
    unset($menu_json->MenusForDays);
    unset($menu_json->ErrorText);
    unset($menu_json->PriceHeader);
    unset($menu_json->Footer);
*/
    file_put_contents("./menus/menu-{$r->id}.json", json_encode($menu_json, 256 | 64));
    file_put_contents("./menus/menu_orig-{$r->id}.json", $json_string);
}
