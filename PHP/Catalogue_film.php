<?php
session_start();
require_once 'Fonction.php';


if (!(isset($_SESSION['username']))) {
    header("location: home_page.php");
    exit;
}

$userID = $_SESSION['id'];

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$moviebypage = 5 ;
$start = ($page - 1) * $moviebypage;

$totalMovies = count_all_movies();
$totalPages = ceil($totalMovies / $moviebypage);
$catalogueMovies = display_all_movie($start, $moviebypage);

$message = '';

if ($userID) {
    $catalogueMovies = display_all_movie($start, $moviebypage);
}

if (isset($_POST['ajouter']) && isset($_POST['movie_id'])) {
    $movieID = $_POST['movie_id'];


    $added = add_movie_to_watchlist($userID, $movieID);

    if ($added) {
        $message = 'Le film a été ajouté à votre watchlist avec succès !';
    } else {
        $message = 'Le film n\'a pas pu être ajouté à votre watchlist, il est peut-être déjà présent.';
    }

    header("location: catalogue_film.php?message=" . urlencode($message));
    exit;
}

$showCatalogue = true;
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