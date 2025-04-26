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

function GetUserWatchlist($userID): array
{
    $pdo = connectDB();

    $stmt = $pdo->prepare("   SELECT 
        movies.id,
        movies.movie_title, 
        movies.poster_url, 
        movies.synopsis 
    FROM users_watchlist
    INNER JOIN movies ON users_watchlist.movie_id = movies.id
    WHERE users_watchlist.user_id = :user_id
    Order By movies.id DESC");
    $stmt->bindParam(':user_id', $userID);
    $stmt->execute();
    return $stmt->fetchAll(pdo::FETCH_ASSOC);
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

















