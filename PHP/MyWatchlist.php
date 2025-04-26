<?php

require_once 'Fonction.php';
session_start();


if (!(isset($_SESSION['username']))) {
    header("location: home_page.php");
    exit ;
}


$userID = $_SESSION['id'];
$movies =[];

if ($userID){
    $movies = GetUserWatchlist($userID );
}

if (isset($_POST['supprimer']) && isset($_POST['movie_id'])) {
    $userID = $_SESSION['id'];
    $movieID = $_POST['movie_id'];


    if (delete_user_movie($userID, $movieID)) {

        header("Location: MyWatchlist.php");
        exit;
    } else {
        echo "Erreur lors de la suppression du film.";
    }
}

$showWatchlist = true;

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