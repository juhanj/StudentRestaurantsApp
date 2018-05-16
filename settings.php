<?php
session_start();
$vege = !empty($_COOKIE['vege']) ? $_COOKIE['vege'] : false;
$food = !empty($_COOKIE['food']) ? $_COOKIE['food'] : false;
$kela = !empty($_COOKIE['kela']) ? $_COOKIE['kela'] : false;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SDSCA - Settings</title>
    <link rel="icon" href="favicon-anim.gif" type="image/gif">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/main.css">
    <script src="./js/main.js"></script>
    <style>
        p {
            margin-top: 4em;
            font-size: 2em;
        }
        p .material-icons {
            font-size: larger;
        }
        label {
            font-size: 2em;
            display: block;
            margin: 2em .5em;
            padding: .5em;
            vertical-align: middle;
        }
        label span {
            vertical-align: middle;
        }
        input[type=checkbox] {
            margin: 0 .5em 0 0;
            transform: scale(4);
            vertical-align: middle;
         }
    </style>
</head>
<body>

<div class="header">
    <a href="list.php"><i class="material-icons">navigate_before</i></a>
    <h1>Settings</h1>
</div>

<form>
    <label>
        <input type="checkbox" id="vegetarian" data-name="vege" <?= $vege ? 'checked' : '' ?>>
        <span>Vegetarian food only</span>
    </label>

    <label>
        <input type="checkbox" id="cafes" data-name="food" <?= $food ? 'checked' : '' ?>>
        <span>Don't include cafeterias</span>
    </label>

    <label>
        <input type="checkbox" id="kela" data-name="kela" <?= $kela ? 'checked' : '' ?>>
        <span>Kela supported only<br>Overrules the cafe option above</span>
    </label>
</form>

<p><a href="fetch_menus.php">Update database<i class="material-icons">refresh</i></a></p>

<script>
    function save_setting( element ) {
        setCookie( element.target.dataset.name, JSON.stringify(Number(element.target.checked)), 999);
    }

    document.getElementById('vegetarian').addEventListener( 'click', save_setting );
    document.getElementById('cafes').addEventListener( 'click', save_setting );
    document.getElementById('kela').addEventListener( 'click', save_setting );
</script>

</body>
</html>
