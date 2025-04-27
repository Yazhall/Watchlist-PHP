<?php
require_once 'Fonction.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: home_page.php");
    exit;
}

$userID = $_SESSION['id'];
$message = '';
$movie = null;
$isCustom = false;
$showEditForm = false;

// Fonction pour chercher un film selon le droit utilisateur
function findMovieForEdit($userID, $movieID, &$isCustom) {
    if ($userID == 1) {
        // Admin : chercher d'abord custom, puis général
        $movie = getCustomMovieById($userID, $movieID);
        if ($movie) {
            $isCustom = true;
            return $movie;
        }
        $movie = getMovieById($movieID);
        $isCustom = false;
        return $movie;
    } else {
        // User normal : chercher uniquement dans les films personnalisés
        $movie = getCustomMovieById($userID, $movieID);
        if ($movie) {
            $isCustom = true;
            return $movie;
        }
        // Sinon, pas autorisé
        $isCustom = false;
        return null;
    }
}

// Vérifier si un ID de film est passé dans l'URL
if (isset($_GET['movie_id'])) {
    $movieID = (int) $_GET['movie_id'];

    $movie = findMovieForEdit($userID, $movieID, $isCustom);

    if (!$movie) {
        $message = "Film introuvable ou non autorisé.";
    } else {
        $showEditForm = true;
    }
} else {
    $message = "Aucun film sélectionné.";
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    // Récupérer l'ID du film depuis le formulaire
    $movieID = (int) $_POST['movie_id'];

    $movie = findMovieForEdit($userID, $movieID, $isCustom);

    if (!$movie) {
        $message = "Film introuvable ou non autorisé.";
    } else {
        $movieTitle = trim($_POST['movie_title']);
        $category = trim($_POST['category']);
        $releaseDate = $_POST['release_date'];
        $Synopsis = trim($_POST['Synopsis']);
        $oldPosterUrl = $movie['poster_url'];

        if (empty($movieTitle) || empty($category) || empty($releaseDate) || empty($Synopsis)) {
            $message = "Tous les champs sont obligatoires.";
        } else {
            // Gestion de l'upload d'image
            if (isset($_FILES['poster_url']) && $_FILES['poster_url']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['poster_url']['tmp_name'];
                $fileName = $_FILES['poster_url']['name'];
                $fileSize = $_FILES['poster_url']['size'];
                $fileType = $_FILES['poster_url']['type'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

                if (in_array($fileType, $allowedTypes)) {
                    $newFileName = uniqid('movie_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                    $uploadDir = $isCustom ? '../image/Poster_movie_add_by_user/' : '../image/Poster_movie/';
                    $uploadFilePath = $uploadDir . $newFileName;

                    if ($fileSize <= 5 * 1024 * 1024) { // 5MB
                        if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                            // Supprimer l'ancienne image si elle existe
                            if (file_exists($oldPosterUrl)) {
                                unlink($oldPosterUrl);
                            }
                            $posterUrlToSave = $uploadFilePath;
                        } else {
                            $message = "Erreur lors du téléchargement de l'image.";
                            $posterUrlToSave = $oldPosterUrl;
                        }
                    } else {
                        $message = "L'image est trop grande (max 5MB).";
                        $posterUrlToSave = $oldPosterUrl;
                    }
                } else {
                    $message = "Format de fichier non autorisé.";
                    $posterUrlToSave = $oldPosterUrl;
                }
            } else {
                // Pas de nouvelle image, on conserve l'ancienne
                $posterUrlToSave = $oldPosterUrl;
            }

            // Mise à jour selon le type de film
            if ($isCustom) {
                updateCustomMovie($userID, $movieID, $movieTitle, $category, $releaseDate, $posterUrlToSave, $Synopsis);
            } else {
                if ($userID == 1) { // SEULEMENT l'admin peut modifier la table movies
                    updateMovie($movieID, $movieTitle, $category, $releaseDate, $posterUrlToSave, $Synopsis);
                } else {
                    $message = "Vous n'avez pas la permission de modifier ce film.";
                    header("Location: MyWatchlist.php?message=" . urlencode($message));
                    exit;
                }
            }

            $message = "Film modifié avec succès 🎉";
            header("Location: MyWatchlist.php?message=" . urlencode($message));
            exit;
        }
    }
}

$showEditForm = true;
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
<body>
<div class="wrapper">
    <?php
    include_once '../html/header.php';
    include_once '../html/main.php';
    include_once '../html/footer.php';
    ?>
</div>
</body>
</html>