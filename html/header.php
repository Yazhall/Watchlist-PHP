<!doctype html>
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
<header class="header">
    <div class="icon-container">
        <a href="../PHP/Home_page.php">
            <img src="/image/movie_11970806.png" alt="icone du site" class="icon_site">
        </a>
    </div>

    <div class="title-container">
        <h1 class="title_site">
            <a href="../PHP/Home_page.php" class="liens">WatchList Film</a>
        </h1>
    </div>

    <nav class="options_header">
        <?php if (isset($_SESSION['username'])) : ?>
            <!-- Si l'utilisateur est connecté, afficher ces liens -->
            <a href="../PHP/dashboard.php" class="liens">DASHBOARD</a>
            <a href="../PHP/logout.php" class="liens">DÉCONNEXION</a>

            <!-- Vérifier si l'utilisateur est l'administrateur -->
            <?php if ($_SESSION['id'] == 1) : ?>
                <a href="../PHP/gestion_bdd_by_admin.php" class="liens">GESTION BDD FILMS</a>
            <?php endif; ?>

        <?php else : ?>
            <!-- Si l'utilisateur n'est pas connecté, afficher ces liens -->
            <a href="../PHP/connexion.php" class="liens">CONNEXION</a>
            <a href="../PHP/Inscription.php" class="liens">INSCRIPTION</a>
        <?php endif; ?>
    </nav>
</header>
</body>
</html>