<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SDSCA - Settings</title>
    <link rel="icon" href="favicon-anim.gif" type="image/gif">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/main.css">
    <style>
        p {
            font-size: 2em;
        }
        p .material-icons {
            font-size: larger;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="list.php"><i class="material-icons">navigate_before</i></a>
    <h1>Settings</h1>
</div>

    <p>Vegetarian food only<i class="material-icons">check_box_outline_blank</i></p>
    <p>Kela supported only<i class="material-icons">check_box</i></p>

    <br><br>

    <p><a href="fetch_menus.php">Update database<i class="material-icons">refresh</i></a></p>

<script>
</script>

</body>
</html>
