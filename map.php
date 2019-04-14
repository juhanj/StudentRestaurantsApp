<?php
$rest_id = !is_null($_GET['id']) ? $_GET['id'] : 'null';
$restaurants = file_get_contents('restaurants.json');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SDSCA - Map</title>
    <meta name="viewport" content="width=device-width">
    <link rel="icon" href="img/favicon-anim.gif" type="image/gif">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/main.css">
    <script src="./js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>

    <style>
        .material-icons {
            font-size: 6em;
        }

        #debug-text {
            font-size: 2em;
        }

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
    <span id="debug-text"></span>
</div>

<script>
    let target_id = <?= $rest_id ?>;
    let debug = document.getElementById('debug-text');
    let restaurants = [];
    let onlyKela, onlyFood;
    let weekDayIndex = moment().isoWeekday()-1;
    let userLocation = { lat : null, lng : null };
    let userLocationChange;
    let userMarker, closestRestaurantMarker;
    let closestRestaurant;
    let map;
    let infoWindow;
    let icons = {
        user : "https://maps.google.com/mapfiles/ms/micons/man.png",
        restaurant : "https://maps.google.com/mapfiles/ms/micons/green-dot.png",
        closed : "https://maps.google.com/mapfiles/ms/micons/red-dot.png",
        closest : "https://maps.google.com/mapfiles/ms/micons/blue-dot.png",
    };

    function geolocationSuccess(pos) {
        userLocation.lat = parseFloat(pos.coords.latitude);
        userLocation.lng = parseFloat(pos.coords.longitude);

        checkClosestRestaurant();

        userMarker = new google.maps.Marker({
            position: userLocation,
            map: map,
            icon : icons.user,
        });

        // Route drawing

        navigator.geolocation.watchPosition( updateMap, error,
            {enableHighAccuracy: false, timeout: 10000, maximumAge: 20 * 1000 });
    }

    function rad(x) {
        return x * Math.PI / 180;
    }

    function haversineDist( user, loc ) {
        let R = 6378137;
        let dLat = rad(loc.lat - user.lat);
        let dLong = rad(loc.long - user.lng);

        let a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(rad(user.lat)) * Math.cos(rad(loc.lat)) *
            Math.sin(dLong / 2) * Math.sin(dLong / 2);

        let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c; // returns the distance in meter
    }

    function checkClosestRestaurant() {
        let shortestDistance = -1;

        restaurants.forEach(function (r) {
            if ( r.marker.icon !== icons.closed ) {
                r.distance = haversineDist( userLocation, r.location );
                if ( shortestDistance === -1 || r.distance < shortestDistance ) {
                    shortestDistance = r.distance;
                    closestRestaurant = r;
                }
            }

        });

        if ( typeof closestRestaurant === "undefined" ) {
            return false;
        }

        if ( typeof closestRestaurantMarker === "undefined" ) {
            closestRestaurantMarker = closestRestaurant.marker;
            closestRestaurantMarker.setIcon(icons.closest);
        }

        else if ( closestRestaurant.marker.icon !== closestRestaurantMarker.icon ) {
            closestRestaurantMarker.setIcon(icons.restaurant);

            closestRestaurantMarker = closestRestaurant.marker;
            closestRestaurantMarker.setIcon(icons.closest);
        }
    }

    function initRestaurantMarker( r ) {
        let link = r.menuUrl_JSON === null
            ? "<a href= " + r.menuUrl + ">Link to Website</a>"
            : "<a href='./menu.php?id=" + r.id + "'>Link to In-app menu</a>";

        let openToday = "";
        let openTomorrow = '';
        let icon = icons.restaurant;

        // Check icon, if place closed, change to red pin
        if ( r.normalLunchHours[weekDayIndex] === null
                || moment().isAfter(moment(r.normalLunchHours[weekDayIndex][1], 'HH:mm'))) {
            icon = icons.closed;
        }

        if ( r.normalLunchHours[weekDayIndex] === null ) {
            openToday = 'Closed!';
        }
        else {
            openToday = r.normalLunchHours[weekDayIndex][0] + "&ndash;" + r.normalLunchHours[weekDayIndex][1];
        }

        if ( weekDayIndex < 7) {
            openTomorrow = (r.normalLunchHours[weekDayIndex+1] === null)
                ? 'Closed!'
                : r.normalLunchHours[weekDayIndex+1][0] + "&ndash;" + r.normalLunchHours[weekDayIndex+1][1];
        } else {
            openTomorrow = (r.normalLunchHours[1] === null)
                ? 'Closed!'
                : r.normalLunchHours[0][0] + "&ndash;" + r.normalLunchHours[0][1];
        }


        r.marker  = new google.maps.Marker({
            position: { lat: r.location.lat, lng: r.location.long },
            map: map,
            icon : icon,
            info:
                "<b>" + r.name + "</b><br>" +
                r.address + "<br>" +
                link + "<br>" +
                "Open today: " + openToday + "<br>" +
                "Tomorrow: " + openTomorrow
        });
        google.maps.event.addListener(r.marker, 'click', function () {
            infoWindow.setContent(r.marker.info);
            infoWindow.open(map, r.marker);
        });

        if ( target_id === r.id ) {
            infoWindow.setContent(r.marker.info);
            infoWindow.open(map, r.marker);
        }
    }

    function updateMap( pos ) {
        // Calculate change of position for user.
        userLocationChange = (userLocation.lat + userLocation.lng)
            - (parseFloat(pos.coords.latitude) + parseFloat(pos.coords.longitude));
        console.log( "userLocationChange: " + userLocationChange );

        // Update userMarker
        userLocation.lat = parseFloat(pos.coords.latitude);
        userLocation.lng = parseFloat(pos.coords.longitude);

        userMarker.setPosition(userLocation);

        // If change in position, update mapCenter to user.
        if ( Math.abs(userLocationChange) > 0.0002 ) {
            map.setCenter(userLocation);
        }

        checkClosestRestaurant();
    }

    function error(err) {
        alert('ERROR(' + err.code + '): ' + err.message);
    }

    function initMap() {

        let startingCenter = target_id === null
            ? new google.maps.LatLng(restaurants[1].location.lat, restaurants[1].location.long)
            : new google.maps.LatLng(restaurants[target_id].location.lat, restaurants[target_id].location.long);

        map = new google.maps.Map( document.getElementById('googleMap'), {
            zoom: 15,
            center: startingCenter,
            fullscreenControl: false,
            disableDefaultUI : true,
            minZoom : 7,
            maxZoom : 19,
        });

        infoWindow = new google.maps.InfoWindow({});

        map.controls[google.maps.ControlPosition.TOP_LEFT].push(header);

        restaurants.forEach( initRestaurantMarker );

        if ( navigator.geolocation ) {
            navigator.geolocation.getCurrentPosition( geolocationSuccess, error);
        }

    }

    restaurants = <?= $restaurants ?>.restaurants;
    onlyKela = (getCookie('kela') === "1");
    onlyFood = (getCookie('food') === "1");

    for(let i = restaurants.length - 1; i >= 0; i--) {
        if ( onlyKela === true && restaurants[i].kela === false ) {
            delete restaurants[i];
        }
        else if ( onlyFood === true && restaurants[i].food === false ) {
            delete restaurants[i];
        }
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQyXJKLhKIYX0XeSZ8TvNJWxr_vOuZf8s&callback=initMap">
</script>

</body>
