<?php
require_once __DIR__ . '/../enums/chemin_page.php';
use App\Enums\CheminPage;
require_once CheminPage::AUTH_MODEL->value;
require_once CheminPage::SESSION_SERVICE->value;
require_once CheminPage::MESSAGE_FR->value;
require_once CheminPage::VALIDATOR_SERVICE->value;

use App\ENUM\VALIDATOR\VALIDATORMETHODE;
use App\Models\AUTHMETHODE;

demarrer_session();

if (isset($_GET['page'])) {
    match ($_GET['page']) {
        'login' => voir_page_login(),
        'resetPassword' => voir_page_reset_password(),
        'logout' => logout(),
        default => null,
    };
}

// === PAGE LOGIN ===
function voir_page_login(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        traiter_connexion();
    } else {
        render('login/login', [], layout: null);
    }
}

function traiter_connexion(): void {
    global $validator, $auth_model;
    $chemin_data = CheminPage::DATA_JSON->value;

    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    $erreurs = $validator[VALIDATORMETHODE::USER->value]($login, $password);
    if (!empty($erreurs)) {
        stocker_session('errors', $erreurs);
        render('login/login', [], layout: null);
        return;
    }

    // Récupérer l'utilisateur uniquement par login (sans vérif du mot de passe ici)
    $user = $auth_model[AUTHMETHODE::LOGIN->value]($login, '', $chemin_data);

    if ($user && password_verify($password, $user['password'])) {
        stocker_session('user', $user);

        $profil = strtolower($user['profil'] ?? '');
        $changer = $user['changer'] ?? false;

        if ($profil === 'apprenant') {
            if ($changer === false || $changer === 'false') {
                render('login/reset_password', [], layout: null);
                return;
            }

            render('apprenant/acceuil', ['user' => $user], layout: null);
            return;
        }

        redirect_to_route('index.php', ['page' => 'liste_promo']);
    } else {
        stocker_session('errors', ['login' => 'login.incorrect']);
        render('login/login', [], layout: null);
    }
}

// === PAGE RESET PASSWORD ===
function voir_page_reset_password(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        traiter_reset_password();
    } else {
        render('login/reset_password', [], layout: null);
    }
}

function traiter_reset_password(): void {
    global $auth_model;
    $chemin_data = CheminPage::DATA_JSON->value;

    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($login) || empty($password)) {
        stocker_session('error', 'Email et mot de passe sont requis');
        render('login/reset_password', [], layout: null);
        return;
    }

    $motDePasseHashe = password_hash($password, PASSWORD_DEFAULT);
    $success = $auth_model[AUTHMETHODE::RESET_PASSWORD->value]($login, $motDePasseHashe, $chemin_data);

    if ($success) {
        stocker_session('success', 'Mot de passe modifié avec succès');
        redirect_to_route('index.php');
    } else {
        stocker_session('error', 'Email introuvable ou erreur de sauvegarde');
        render('login/reset_password', [], layout: null);
    }
}

// === DECONNEXION ===
function logout(): void {
    demarrer_session();
    detruire_session();
    redirect_to_route('index.php');
}
