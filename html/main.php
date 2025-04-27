<?php
global $categories, $categories_in_watchlist, $customMovies;
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
            <div class="category-menu">
                <form method="GET">
                    <label>
                        <select name="category" onchange="this.form.submit()">
                            <option value="">-- Toutes les catégories --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['category']) ?>" <?= (isset($_GET['category']) && $_GET['category'] == $category['category']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['category']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </form>
            </div>

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
                                <?php if ($_SESSION['id'] == 1) : ?>
                                    <form method="GET" action="../PHP/edit_custom_movie.php">
                                        <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                                        <button type="submit" class="btn-modifier">Modifier le film</button>
                                    </form>
                                    <form method="GET" action="">
                                        <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                                        <button type="submit" name="supprimer" class="btn-supprimer">Supprimer</button>
                                    </form>


                                <?php endif; ?>
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


            <h2>🎬 Ma Watchlist</h2>
            <div class="category-menu">
                <form method="GET">
                    <label>
                        <select name="category" onchange="this.form.submit()">
                            <option value="">-- Toutes les catégories --</option>
                            <?php foreach ($categories_in_watchlist as $category): ?>
                                <option value="<?= htmlspecialchars($category['category']) ?>" <?= (isset($_GET['category']) && $_GET['category'] == $category['category']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['category']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </form>
            </div>

            <div class="watchlist-container">
                <?php if (!empty($watchlistMovie)): ?>
                    <?php foreach ($watchlistMovie as $movie): ?>
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

                <!-- Afficher les films personnalisés seulement sur la première page -->
                <?php if ($page == 1 && !empty($customMovies)): ?>
                    <?php foreach ($customMovies as $movie): ?>
                        <div class="movie-card">
                            <img src="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/' . htmlspecialchars($movie['poster_url']) ?>"
                                 alt="<?= htmlspecialchars($movie['movie_title']) ?>" class="movie-poster">
                            <h3><?= htmlspecialchars($movie['movie_title']) ?></h3>
                            <p><?= htmlspecialchars($movie['Synopsis']) ?></p>

                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="movie-custom-id" value="<?= $movie['id'] ?>">
                                <button type="submit" name="supprimer" class="btn-supprimer">Supprimer</button>
                            </form>


                            <form method="GET" action="../PHP/edit_custom_movie.php">
                                <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                                <button type="submit" class="btn-modifier">Modifier le film</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
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
        <?php if (isset($showAddmovies) && $showAddmovies): ?>
            <h2>➕ Ajouter un Film</h2>

            <?php if (!empty($message)): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="formulaire">
                <label for="movie_title">Titre du film :</label>
                <input type="text" id="movie_title" name="movie_title" required>

                <label for="category">Catégorie :</label>
                <input type="text" id="category" name="category" required>

                <label for="release_date">Date de sortie :</label>
                <input type="date" id="release_date" name="release_date" required>

                <label for="synopsis">Synopsis :</label>
                <textarea id="synopsis" name="synopsis" rows="5" required></textarea>

                <label for="poster">Affiche du film :</label>
                <input type="file" id="poster" name="poster" accept="image/*" required>

                <button type="submit" name="submit" class="btn-ajouter">Ajouter</button>
            </form>
        <?php endif; ?>


        <?php if (isset($showEditForm) && $showEditForm): ?>
            <h2>➕ Modifier un Film</h2>

            <?php if (!empty($message)): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="formulaire">
                <input type="hidden" name="movie_id" value="<?= htmlspecialchars($movie['id'] ?? '') ?>">

                <label for="movie_title">Titre du film :</label>
                <input type="text" id="movie_title" name="movie_title"
                       value="<?= htmlspecialchars($movie['movie_title'] ?? '') ?>" required><br><br>

                <label for="category">Catégorie :</label>
                <input type="text" id="category" name="category"
                       value="<?= htmlspecialchars($movie['category'] ?? '') ?>" required><br><br>

                <label for="release_date">Date de sortie :</label>
                <input type="date" id="release_date" name="release_date"
                       value="<?= htmlspecialchars($movie['release_date'] ?? '') ?>" required><br><br>

                <label for="poster_url">Nouvelle image (si vous souhaitez changer l'image) :</label>
                <input type="file" id="poster_url" name="poster_url" accept="image/*"><br><br>

                <label for="Synopsis">Synopsis :</label>
                <textarea name="Synopsis" id="synopsis"
                          required><?= htmlspecialchars($movie['Synopsis'] ?? '') ?></textarea><br><br>

                <button type="submit" name="modifier">Modifier le film</button>
            </form>
        <?php endif; ?>


        <!-- Affichage accueil -->
        <?php if (isset($showdashboard) && $showdashboard): ?>
            <?php if (isset($_SESSION['username'])): ?>
                <h2>Bienvenue <?= htmlspecialchars($_SESSION['username']) ?> 👋</h2>
                <nav class="nav">
                    <a class="liens" href="../PHP/Catalogue_film.php">➕ Ajouter un film a votre watchlist</a>
                    <a class="liens" href="../PHP/MyWatchlist.php">🎬 Voir ma watchlist</a>
                    <a class="liens" href="../PHP/add_movie_by_user.php">➕ Ajouter un Film Perso a votre watchlist </a>
                </nav>
            <?php endif; ?>
        <?php endif; ?>


    </div>
</main>

</body>
</html>