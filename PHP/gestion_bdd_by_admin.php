<?php

require_once 'Fonction.php';
require_once  'Catalogue_film.php';
require_once  'add_movie_by_user.php';


if (!(isset($_SESSION['username']))) {
    header("location: home_page.php");
    exit;
}

$showgestion_bdd_by_admin =true ;
?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../CSS/Style.css/">
    <title>Acceuil</title>
</head>
<body >
<div class="wrapper">
    <?php
    include_once '../html/header.php';
    include_once '../html/main.php';
    include_once '../html/footer.php';
    ?>


</div>


</body>
</html>
