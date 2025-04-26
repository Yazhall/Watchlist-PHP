<?php
if (!isset($page)) {
    $page = 1;
}
if (!isset($totalPages)) {
    $totalPages = 1;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../CSS/Style.css">
    <title>Acceuil</title>
</head>
<body class="wrapper">
<main class="main">
    <div class="main-content">
        <?php if (isset($showCatalogue) && $showCatalogue): ?>
            <h2>🎞️ Catalogue des films</h2>

            <?php if (!empty($message)): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <div class="watchlist-container">
                <?php if (!empty($catalogueMovies)): ?>
                    <?php foreach ($catalogueMovies as $movie): ?>
                        <div class="movie-card">
                            <img src="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/' . htmlspecialchars($movie['poster_url']) ?>"
                                 alt="poster" class="movie-poster">
                            <div>
                                <h3><?= htmlspecialchars($movie['movie_title']) ?></h3>
                                <p><?= htmlspecialchars($movie['synopsis']) ?></p>
                                <form method="POST">
                                    <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                                    <button type="submit" name="ajouter" class="btn-ajouter">Ajouter</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun film disponible pour l’instant.</p>
                <?php endif; ?>
            </div>


            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="pagination-btn">« Précédent</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="pagination-btn <?= ($i == $page) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="pagination-btn">Suivant »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>



        <?php if (isset($showWatchlist) && $showWatchlist): ?>
            <?php $userID = $_SESSION['id']; ?>
            <?php $movies = GetUserWatchlist($userID); ?>

            <h2>🎬 Ma Watchlist</h2>
            <div class="watchlist-container">
                <?php if (!empty($movies)): ?>
                    <?php foreach ($movies as $movie): ?>
                        <div class="movie-card">
                            <img src="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/' . htmlspecialchars($movie['poster_url']) ?>"
                                 alt="<?= htmlspecialchars($movie['movie_title']) ?>" class="movie-poster">
                            <h3><?= htmlspecialchars($movie['movie_title']) ?></h3>
                            <p><?= htmlspecialchars($movie['synopsis']) ?></p>
                            <form method="POST">
                                <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                                <button type="submit" name="supprimer" class="btn-supprimer">Supprimer</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Votre watchlist est vide.</p>
                <?php endif; ?>
            </div>

            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="pagination-btn">« Précédent</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="pagination-btn <?= ($i == $page) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="pagination-btn">Suivant »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Affichage accueil -->
        <?php if (!(isset($showCatalogue) && $showCatalogue) && !(isset($showWatchlist) && $showWatchlist)): ?>
            <?php if (isset($_SESSION['username'])): ?>
                <h2>Bienvenue <?= htmlspecialchars($_SESSION['username']) ?> 👋</h2>
                <nav class="nav">
                    <a class="liens" href="../PHP/Catalogue_film.php">➕ Ajouter un film</a>
                    <a class="liens" href="../PHP/MyWatchlist.php">🎬 Voir ma watchlist</a>
                    <a class="liens" href="../PHP/logout.php">🚪 Déconnexion</a>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

</body>
</html>