<?php
/*============
 * FUNCTIONS *
 ============*/
/*========
 * LOGIC *
 ========*/
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
    function getLocation() {
        if (!navigator.geolocation) {
            return false;
        }

        function success( position ) {
            setCookie( "location", JSON.stringify([position.coords.latitude,position.coords.longitude]), 0 );
        }
        function error() {
            setCookie( "location", JSON.stringify("null"), 0 );
        }

        navigator.geolocation.getCurrentPosition( success, error );
        return true;
    }

    let test = getLocation();

    console.log(test);
</script>

</body>
</html>
