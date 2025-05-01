<?php

require_once __DIR__ . '/../../enums/chemin_page.php';

use App\Enums\CheminPage;

$url = "http://" . $_SERVER["HTTP_HOST"];
$css_login = CheminPage::CSS_LOGIN->value;
$logo_image = CheminPage::IMG_LOGO_LOGIN->value;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $url . $css_login ?>">
    <title>Changer Mot de passe </title>
</head>

<body>
    <div class="form-container with-shadow">
        <div class="logo">
            <img class="logo-img" src="<?= $url . $logo_image ?>" alt="logo Sonatel">
        </div>
        <p class="welcome">
            Bienvenue sur <br>
            <span class="academy">Ecole du code Sonatel Academy</span>
        </p>
        <p class="main-title">Changer Mot de passe </p>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="form" method="POST" action="?page=resetPassword">
            <label for="email">Email</label>
            <input
                type="text"
                name="login"
                id="email"
                class="input <?= !empty($_SESSION['error']) ? 'alert' : '' ?>"
                placeholder="entrer votre email ">
            <label for="password">Mot de passe </label>
            <input
                type="password"
                name="password"
                id="password"
                class="input masque <?= !empty($_SESSION['error']) ? 'alert' : '' ?>"
                placeholder="entrer votre mot de passe">

            <button type="submit" class="form-btn">Changer</button>
        </form>
    </div>
</body>

</html>