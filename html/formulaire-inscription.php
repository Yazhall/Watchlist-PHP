<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../CSS/Style.css">
    <title>Inscription</title>
</head>
<body >
<main class="main">

    <div>
        <img src="" alt="" class="">
    </div>
    <div>
        <form method="post" class="formulaire">

            <label for="firstname">
                <input type="text" name="firstname" placeholder="firstname">
            </label>
            <label for="lastname">
                <input type="text" name="lastname" placeholder="lastname">
            </label>
            <label for="tel">
                <input type="tel" name="tel" placeholder="tel">
            </label>
            <label for="username">
                <input type="text" name="username" placeholder="username">
            </label>
            <label for="email">
                <input type="email" name="email" placeholder="email">
            </label>
            <label for="password">
                <input type="password" name="password" placeholder="password">
            </label>
            <input type="submit">
        </form>
    </div>
    <?php if (!empty($message)) : ?>
        <p class="message" style="color: <?= str_starts_with($message, '✅') ? 'green' : 'red' ?>;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;">❌ Erreur : <?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

</main>
</body>
</html>