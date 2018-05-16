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

$restaurants = file_get_contents('restaurants.json');
//debug($restaurants)?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SDSCA - Map</title>
    <meta name="viewport" content="width=device-width">
    <link rel="icon" href="favicon-anim.gif" type="image/gif">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/main.css">
    <script src="./js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>

    <style>

        .map {
            height: 100%;
            width: 100%;
            position: absolute;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>

<div id="googleMap" class="map"></div>
<div id="header">
    <a href="list.php"><i class="material-icons">menu</i></a>
</div>

<script>
    let foo;
    let weekDayIndex = moment().isoWeekday();
    let mapDiv = document.getElementById('googleMap');
    let userLocation = { lat : null, lng : null };
    let userLatLng;
    let userMarker;
    let map;
    let infoWindow;
    let icons = {
        user : "https://maps.google.com/mapfiles/ms/micons/man.png",
        restaurant : "https://maps.google.com/mapfiles/ms/micons/green-dot.png",
        closed : "https://maps.google.com/mapfiles/ms/micons/red-dot.png",
        closest : "https://maps.google.com/mapfiles/ms/micons/blue-dot.png",
    };

    function getUserLocation() {
        if (!navigator.geolocation) {
            return false;
        }

        function success(position) {
            userLocation.lat = position.coords.latitude;
            userLocation.lng = position.coords.longitude;
        }

        function error() {
            console.error("User location ERROR");
            return false;
        }

        navigator.geolocation.getCurrentPosition(success, error);
        return true;
    }

    function initRestaurantMarker( r ) {
        let link = r.menuUrl_JSON === null
            ? "<a href= " + r.menuUrl + ">Website</a><br>"
            : "<a href= " + r.menuUrl_JSON + ">In-app menu</a><br>";

        let openToday = "";
        let label = '';
        let icon = icons.restaurant;

        if ( r.normalLunchHours[weekDayIndex] === null ) {
            openToday = 'Closed!';
            label = 'X';
            icon = icons.closed;
        }
        else {
            openToday = r.normalLunchHours[weekDayIndex][0] + "&ndash;" + r.normalLunchHours[weekDayIndex][1];
            icon = moment().isAfter(moment(r.normalLunchHours[weekDayIndex][1], 'HH:mm'))
                ? icons.closed
                : icons.restaurant;
        }

        r.marker  = new google.maps.Marker({
            position: { lat: r.location.lat, lng: r.location.long },
            map: map,
            label : label,
            icon : icon,
            info:
                "<b>" + r.name + "</b><br>" +
                r.address + "<br>" +
                link +
                "Open today: " + openToday
        });
        google.maps.event.addListener(r.marker, 'click', function () {
            infoWindow.setContent(r.marker.info);
            infoWindow.open(map, r.marker);
        });
    }

    function updateMap() {
        // All the functionality here
    }
    function error(err) {
        alert('ERROR(' + err.code + '): ' + err.message);
    }

    function initMap() {
        let startingCenter = userLocation.lat === null
            ? new google.maps.LatLng(62.600549742, 29.7467832)
            : userLocation;
        map = new google.maps.Map(mapDiv, {
            zoom: 14,
            center: startingCenter,
            fullscreenControl: false,
            streetViewControl: false,
            zoomControl: false,
            mapTypeControl: false
        });

        infoWindow = new google.maps.InfoWindow({});

        map.controls[google.maps.ControlPosition.TOP_LEFT].push(header);

        restaurants.forEach( initRestaurantMarker );

        if (userLocation.lat !== null) {
            userMarker = new google.maps.Marker({
                position: userLocation,
                map: map,
                icon : icons.user,
            });

            navigator.geolocation.watchPosition( updateMap, error );
        }

    }

    getUserLocation();
    let restaurants = <?= $restaurants ?>.restaurants;
</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQyXJKLhKIYX0XeSZ8TvNJWxr_vOuZf8s&callback=initMap">
</script>

</body>
