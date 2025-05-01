<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../enums/chemin_page.php';

use App\Enums\CheminPage;

$url = "http://" . $_SERVER["HTTP_HOST"];
$css_login = CheminPage::CSS_LOGIN->value;
$logo_image = CheminPage::IMG_LOGO_LOGIN->value;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $url . $css_login ?>">
    <title>Login</title>
</head>

<body>


    <div class="form-container with-shadow">
        <div class="logo">
            <img class="logo-img" src="<?= $url . $logo_image ?>" alt="logo Sonatel">
        </div>
        <p class="welcome">Bienvenue sur<br><span class="academy">Ecole du code Sonatel Academy</span></p>
        <p class="main-title">Se connecter</p>
        <?php
        // Démarrer la session et charger les erreurs
        require_once CheminPage::ERROR_FR->value;

        use App\ENUM\ERREUR\ErreurEnum;

        $erreurs = recuperer_session('errors', []);
        $success = recuperer_session('success', []);

        ?>

        <form class="form" method="POST" action="">
            <?php if (!empty($success)): ?>
                <div class="alert-succes"><?= $success ?></div>
            <?php endif; ?>

            <label for="login">Email</label>
            <input type="text" id="login" name="login" class="input" placeholder="Matricule ou email" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
            <?php if (isset($erreurs['login'])): ?>
                <p style="color:red"><?= $error[$erreurs['login']] ?></p>

            <?php endif; ?>

            <label for="password">Mot de passe</label>
            <input type="text" id="password" name="password" class="input" placeholder="Mot de passe">
            <?php if (isset($erreurs['password'])): ?>
                <p style="color:red"><?= $error[$erreurs['password']] ?></p>


            <?php endif; ?>

            <a href="?page=resetPassword" class="page-link"> <span class="page-link-label">Mot de passe oublié ?</span></a>

            <button class="form-btn">Se connecter</button>
        </form>
    </div>
    <?php unset($_SESSION['success']); ?>

    <?php unset($_SESSION['errors']); ?>


</body>

</html>