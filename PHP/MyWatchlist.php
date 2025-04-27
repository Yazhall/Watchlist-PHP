<?php

require_once 'Fonction.php';
session_start();

if (!(isset($_SESSION['username']))) {
    header("location: home_page.php");
    exit;
}

$userID = $_SESSION['id'];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // récupère la page actuelle
$movieperpage = 5;
$start = ($page - 1) * $movieperpage;

$totalMovieInWatchlist = countMovieInUserWatchlist($userID);

// Films personnalisés
$customMovies = getCustomMoviesByUser($userID);

$categories_in_watchlist = GetCategoriesInWatchlist($userID);
$selectedCategory = $_GET['category'] ?? null;

if ($selectedCategory) {
    $watchlistMovie = display_movies_by_category_in_watchlist($userID, $selectedCategory, $start, $movieperpage);
    $totalMovieInWatchlist = countMoviesInUserWatchlistByCategory($userID, $selectedCategory);
} else {
    $watchlistMovie = displayUserWatchlist($userID, $start, $movieperpage);
    $totalMovieInWatchlist = countMovieInUserWatchlist($userID);
}

$totalPages = ceil($totalMovieInWatchlist / $movieperpage);
$message = '';

// Suppression d'un film
if (isset($_POST['supprimer'])) {
    // Suppression d'un film dans la watchlist
    if (isset($_POST['movie_id'])) {
        $userID = $_SESSION['id'];
        $movieID = $_POST['movie_id'];

        if (delete_user_movie($userID, $movieID)) {
            header("location: MyWatchlist.php?message=" . urlencode("Film supprimé de la watchlist.") . "&category=" . urlencode($selectedCategory) . "&page=" . $page);
            exit;
        } else {
            $message = "Erreur lors de la suppression du film.";
        }
    }

    // Suppression d'un film personnalisé
    if (isset($_POST['movie-custom-id'])) {
        $userID = $_SESSION['id'];
        $movieID = $_POST['movie-custom-id'];

        if (delete_custom_movie($userID, $movieID)) {
            header("location: MyWatchlist.php?message=" . urlencode("Film personnalisé supprimé.") . "&category=" . urlencode($selectedCategory) . "&page=" . $page);
            exit;
        } else {
            $message = "Erreur lors de la suppression du film personnalisé.";
        }
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
    <title>Watchlist</title>
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