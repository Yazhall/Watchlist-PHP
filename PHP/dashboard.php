<?php
session_start();

 if (!(isset($_SESSION['username']))) {
     header("location: home_page.php");
     exit ;
 }
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../CSS/Style.css">
    <title>Acceuil</title>
</head>
<body>

<?php
include_once '..\html\header.php';
?>
<main>
        <h2>
            Bienvenue <?= htmlspecialchars($_SESSION['username']) ?>
        </h2>
        <a href="" > Ajoute un film </a>
        <a href=""> Voir ma watchlist</a>
</main>

<?php
include_once '..\html\footer.php';
?>

</body>
</html>
