<?php

session_start();
require_once 'Fonction.php';

if (!(isset($_SESSION['id']))) {
    header("location: home_page.php");
    exit;
}
$userID = $_SESSION['id'];

if (isset($_POST['submit'])) {
    $pdo = connectDB();

    // Validation des champs
    $movieTitle = trim($_POST['movie_title']);
    $category = trim($_POST['category']);
    $releaseDate = $_POST['release_date'];
    $synopsis = trim($_POST['synopsis']);

    if (empty($movieTitle) || empty($category) || empty($releaseDate)) {
        $message = "Tous les champs sont obligatoires.";
    } else {
        // Upload de l'image
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['poster']['tmp_name'];
            $fileName = $_FILES['poster']['name'];
            $fileSize = $_FILES['poster']['size'];
            $fileType = $_FILES['poster']['type'];

            // Spécifier le répertoire où stocker l'image
            if (!is_dir('../images/Poster_movie_add_by_user')) {
                mkdir('../images/Poster_movie_add_by_user', 0777, true); // Créer le dossier si il n'existe pas
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

            // Vérifier si le type de fichier est autorisé
            if (in_array($fileType, $allowedTypes)) {
                // Générer un nom de fichier unique pour éviter les collisions
                $newFileName = uniqid('movie_' . time(), true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $uploadDir = '../images/Poster_movie_add_by_user/';
                $uploadFilePath = $uploadDir . $newFileName;

                // Vérifier la taille du fichier (ex: max 5MB)
                $maxFileSize = 5 * 1024 * 1024; // 5MB
                if ($fileSize > $maxFileSize) {
                    $message = "L'image est trop grande. La taille maximale est de 5MB.";
                } else {
                    // Déplacer le fichier téléchargé vers le répertoire spécifié
                    if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                        // Appeler la fonction d'ajout du film avec le chemin de l'image
                        if (addCustomMovie($_SESSION['id'], $movieTitle, $category, $releaseDate, $uploadFilePath, $synopsis)) {
                            $message = "Film ajouté avec succès ! 🎉";
                        } else {
                            $message = "Erreur lors de l'ajout du film dans la base de données.";
                        }
                    } else {
                        $message = "Erreur lors de l'upload de l'image.";
                    }
                }
            } else {
                $message = "Le type de fichier n'est pas autorisé. Seuls les fichiers JPG, PNG et GIF sont autorisés.";
            }
        } else {
            // Si aucun fichier n'est téléchargé, on l'indique
            $message = "Aucune image n'a été téléchargée.";
        }
    }
}

$showAddmovies = true;

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
