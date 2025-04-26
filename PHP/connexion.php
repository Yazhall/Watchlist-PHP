<?php

use Random\RandomException;

require_once 'Fonction.php';

try {

    $message = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $identifiant = trim($_POST["identifiant"] ?? '');
        $password = trim($_POST["password"] ?? '');


        if (empty($identifiant) || empty($password)) {
        $message = "❗Veuillez remplir tous les champs.";
    } else {
        $user = checkUser($identifiant);

        if ($user && verifyLogin($user, $password)) {
            session_start();
            loginUser($user);
        } else {
            $message = "❌ Identifiants incorrects.";
        }
    }
}



}catch (PDOException $e){
    echo $e->getMessage();
} catch (  RandomException $e) {
}


?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../CSS/Style.css/">
    <title>Connexion</title>
</head>
<body>
<div class="wrapper">
<?php
    include_once '../html/header.php';
    include_once '../html/formulaire-connexion.php';
    include_once '../html/footer.php';
    ?>
</div>
</body>
</html>


