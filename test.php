<?php declare(strict_types=1);
require './class/dbyhteys.class.php';

$db = new DByhteys();

print_r( $db );


$sql = "select id, name, json_url, latitude, longitude, food, kela, address, city, 
				m.url as website_url
			from restaurant r
			join menuurls m 
				on r.id = m.restaurant_id
				and ? = m.language";

print_r( $db->query( $sql , ['eng'] , FETCH_ALL ) );


