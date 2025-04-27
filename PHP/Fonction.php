<?php

use JetBrains\PhpStorm\NoReturn;
use Random\RandomException;

function connectDB()
{
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=watchlist', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (pdoException $e) {
        die ("erreur de connexion la BDD :" . $e->getMessage());
    }
}

function createUser($firstname, $lastname, $tel, $username, $email, $password): bool
{
    $pdo = connectDB();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (firstname, lastname, tel, username, email, password) Values(:firstname, :lastname, :tel, :username, :email, :password)";

    try {
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':tel', $tel);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);


        return $stmt->execute();

    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }

}


function checkUser($identifiant)
{

    $pdo = connectDB();

    $sql = "SELECT * FROM user WHERE username = :identifiant OR email = :identifiant";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':identifiant', $identifiant);
    $stmt->execute();

    return $stmt->fetch(pdo::FETCH_ASSOC);
}


function verifyLogin($user, $password): bool
{
    return $user && password_verify($password, $user['password']);
}

/**
 * @throws RandomException  // sinon une erreur
 */
#[NoReturn] function loginUser($user): void
{
    session_regenerate_id(true);
    $token = bin2hex(random_bytes(32));
    $_SESSION ['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['token'] = $token;

    setcookie('token', $token, time() + 60 * 60 * 24);
    header('location: dashboard.php');
    exit;
}

function displayUserWatchlist($userID, $start, $movieperpage): array
{
    $pdo = connectDB();
    $stmt = $pdo->prepare("   SELECT 
        movies.id,
        movies.movie_title, 
        movies.poster_url,
        movies.category,
        movies.synopsis 
    FROM users_watchlist
    INNER JOIN movies ON users_watchlist.movie_id = movies.id
    WHERE users_watchlist.user_id = :user_id
    Order By movies.id DESC
    LIMIT :debut, :moviesbypage");
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
    $stmt->bindValue(':debut', $start, PDO::PARAM_INT);
    $stmt->bindValue(':moviesbypage', $movieperpage, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(pdo::FETCH_ASSOC);
}


function display_movies_by_category_in_watchlist($userID, $categoryName, $start, $movieperpage): array {
    $pdo = connectDB();

    $stmt = $pdo->prepare("
        SELECT movies.id, movies.movie_title, movies.category, movies.poster_url, movies.synopsis
        FROM movies
        JOIN users_watchlist ON movies.id = users_watchlist.movie_id
        WHERE users_watchlist.user_id = :user_id
        AND movies.category = :category
        ORDER BY movies.movie_title
        LIMIT :debut, :moviesbypage
    ");

    $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
    $stmt->bindValue(':category', $categoryName);
    $stmt->bindValue(':debut', $start, PDO::PARAM_INT);
    $stmt->bindValue(':moviesbypage', $movieperpage, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function GetCategoriesInWatchlist($userID): array
{
    $pdo = connectDB();

    $stmt = $pdo->prepare("
        SELECT DISTINCT category
        FROM movies
        JOIN users_watchlist ON movies.id = users_watchlist.movie_id
        WHERE users_watchlist.user_id = :user_id
        ORDER BY category
    ");

    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function countMovieInUserWatchlist($userID): int
{
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users_watchlist WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
    $stmt->execute();
    return (int)$stmt->fetchColumn();
}

function countMoviesInUserWatchlistByCategory($userID, $category): int
{
    $pdo = connectDB();

    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM users_watchlist
        INNER JOIN movies ON users_watchlist.movie_id = movies.id
        WHERE users_watchlist.user_id = :user_id
        AND movies.category = :category
    ");

    $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
    $stmt->bindValue(':category', $category  );

    $stmt->execute();

    return (int) $stmt->fetchColumn();
}




function delete_user_movie($userID, $movieID): bool
{
    $pdo = connectDB();
    $sql = "DELETE FROM users_watchlist WHERE user_id = :user_id AND movie_id = :movie_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userID);
    $stmt->bindParam(':movie_id', $movieID);
    return $stmt->execute();
}

function delete_custom_movie($userID, $movieID): bool
{
    $pdo = connectDB();
    $sql = "DELETE FROM users_custom_movies WHERE user_id = :user_id AND id= :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userID);
    $stmt->bindParam(':id', $movieID);
    return $stmt->execute();

}


function get_film_title($movieID): string
{
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT movie_title FROM movies WHERE id = :movie_id");
    $stmt->bindParam(':movie_id', $movieID);
    $stmt->execute();
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$movie) {
        return '';
    }
    return $movie['movie_title'];
}

function check_movie_in_watchlist($userID, $movieID): bool
{
    $pdo = connectDB();
    $sqlcheck = "SELECT * FROM users_watchlist WHERE user_id = :user_id AND movie_id = :movie_id";
    $stmtcheck = $pdo->prepare($sqlcheck);
    $stmtcheck->bindParam(':user_id', $userID);
    $stmtcheck->bindParam(':movie_id', $movieID);
    $stmtcheck->execute();

    if ($stmtcheck->rowCount() > 0) {
        return false;
    }
    return true;
}

function add_movie_to_watchlist($userID, $movieID): bool
{
    $pdo = connectDB();


    $movieTitle = get_film_title($movieID);


    if ($movieTitle === '') {
        return false;
    }


    if (!check_movie_in_watchlist($userID, $movieID)) {
        return false;
    }


    $sql = "INSERT INTO users_watchlist (user_id, movie_id, movie_title) VALUES (:user_id, :movie_id, :movie_title)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userID);
    $stmt->bindParam(':movie_id', $movieID);
    $stmt->bindParam(':movie_title', $movieTitle);

    return $stmt->execute();
}

function display_all_movie($start, $moviebypage): array
{
    $pdo = connectDB();
    $stmt= $pdo->prepare("SELECT * FROM movies LIMIT :debut, :moviesbypage");
    $stmt->bindParam(':debut', $start , PDO::PARAM_INT);
    $stmt->bindParam(':moviesbypage', $moviebypage, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

function count_all_movies()
{
    $pdo = connectDB();
    $stmt = $pdo->query("SELECT COUNT(*) FROM movies");
    return $stmt->fetchColumn();
}


function get_all_categories(): array
{
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT DISTINCT category FROM movies ORDER BY category ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function display_movies_by_category($categoryName, $start, $moviebypage): array
{
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE category = :category LIMIT :debut, :moviebypage");
    $stmt->bindValue(':category', $categoryName);
    $stmt->bindValue(':debut', $start, PDO::PARAM_INT);
    $stmt->bindValue(':moviebypage', $moviebypage, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function count_movies_by_category($categoryName){
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM movies WHERE category = :categoryID");
    $stmt->bindParam(':categoryID', $categoryName, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}




function getCustomMoviesByUser($userID): array
{
    $pdo = connectDB();

    $stmt = $pdo->prepare("SELECT * FROM users_custom_movies WHERE user_id = :user_id ");
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function addCustomMovie($userId, $movieTitle, $category, $releaseDate, $posterPath, $Synopsis): bool
{
    $pdo = connectDB();

    $stmt = $pdo->prepare("
        INSERT INTO users_custom_movies (user_id, movie_title, category, release_date, poster_url,Synopsis)
        VALUES (:user_id, :movie_title, :category, :release_date, :poster_url,:Synopsis)
    ");
    $stmt->bindParam(':user_id', $userId,PDO::PARAM_INT);
    $stmt->bindParam(':movie_title', $movieTitle);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':release_date', $releaseDate);
    $stmt->bindParam(':poster_url', $posterPath);
    $stmt->bindParam(':Synopsis', $Synopsis);
    return $stmt->execute();

}


function getCustomMovieById($userID, $id): array {
    $pdo = connectDB();
    $sql = "SELECT * FROM users_custom_movies WHERE user_id = :user_id AND id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // Si aucun film trouvé, retourne un tableau vide
    return $result ? $result : [];
}

function updateCustomMovie($userID, $movieID, $movie_title, $category, $releaseDate, $posterUrl, $Synopsis): bool
{
    $pdo = connectDB();
    $sql = "UPDATE users_custom_movies 
            SET movie_title = :movie_title, category = :category, release_date = :release_date, 
                poster_url = :poster_url, Synopsis = :Synopsis 
            WHERE user_id = :user_id AND id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':movie_title', $movie_title);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':release_date', $releaseDate);
    $stmt->bindParam(':poster_url', $posterUrl);
    $stmt->bindParam(':Synopsis', $Synopsis);
    $stmt->bindParam(':user_id', $userID);
    $stmt->bindParam(':id', $movieID);
    return $stmt->execute();
}


function updateMovie($movieID, $movieTitle, $category, $releaseDate, $posterUrl, $Synopsis): bool
{
    $pdo = connectDB();  // Assure-toi d'avoir une connexion PDO active

    // Préparer la requête de mise à jour
    $sql = "UPDATE movies 
            SET movie_title = :movieTitle, 
                category = :category, 
                release_date = :releaseDate, 
                poster_url = :posterUrl, 
                synopsis = :Synopsis 
            WHERE id = :movieID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':movieTitle', $movieTitle);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':releaseDate', $releaseDate);
    $stmt->bindParam(':posterUrl', $posterUrl);
    $stmt->bindParam(':Synopsis', $Synopsis);
    $stmt->bindParam(':movieID', $movieID, PDO::PARAM_INT);

    // Exécuter la requête
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function getMovieById($movieID) {
    $pdo = connectDB();


    $sql = "SELECT * FROM movies WHERE id = :movieID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':movieID', $movieID, PDO::PARAM_INT);

    // Exécuter la requête et retourner le film
    if ($stmt->execute()) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}





