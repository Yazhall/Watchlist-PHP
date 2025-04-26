<?php
require_once 'Fonction.php';
try {


$pdo = connectDB();
$message = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST["firstname"] ?? '');
    $lastname = trim($_POST["lastname"] ?? '');
    $tel = trim($_POST["tel"] ?? '');
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');

        if (empty($firstname) || empty($lastname) || empty($tel) || empty($username) || empty($email ||empty($password) )) {
            $message = ' Veuillez remplir tous les champs';
        } else {
            if (createUser($firstname, $lastname,$tel, $username, $email, $password)) {
                $message = '✅ Inscription réussie';
            } else {
                $message = '❌ Une erreur est survenue';
            }
        }


}
}catch (PDOException $e){
    $errorMessage = urlencode($e->getMessage());
    header('Location:inscription.php?errorMessage='.$errorMessage);
    exit;
}
?>

<?php

?>

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
<div class="wrapper">
<?php
include_once '../html/header.php';
include_once '../html/formulaire-inscription.php';
include_once '../html/footer.php';
?>
</div>


</body>
</html>


