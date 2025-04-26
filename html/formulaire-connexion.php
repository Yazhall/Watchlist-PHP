<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../CSS/Style.css">
    <title>Connexion</title>
</head>
<body >
<main class="main">
    <div>
        <img src="" alt="" class="">
    </div>
    <div>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form method="post" action="../PHP/connexion.php" class="formulaire">
            <label for="identifiant">
                <input type="text" name="identifiant" placeholder="Nom d'utilisateur ou email">
            </label>
            <label for="password">
                <input type="password" name="password" placeholder="password">
            </label>
            <input type="submit" value="Connexion" >
        </form>
    </div>


</main>
</body>
</html>