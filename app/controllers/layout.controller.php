<?php
require_once __DIR__ . '/../enums/chemin_page.php';
use App\Enums\CheminPage;
require_once CheminPage::SESSION_SERVICE->value;
require_once CheminPage::CONTROLLER->value;

demarrer_session();

if (!session_existe('user')) {
    redirect_to_route('index.php', ['page' => 'login']);
    exit;
}

$page = $_GET['content'] ?? 'liste_promo';

// Liste sécurisée des pages possibles
$pages_valides = [
    'liste_promo' => CheminPage::VIEW_PROMO->value,
    //'ajouter_promo' => CheminPage::AJOUTER_PROMO_VIEW->value,
    // ajoute d’autres si nécessaire
];

// On sécurise pour éviter l’inclusion arbitraire
$page_content = $pages_valides[$page] ?? CheminPage::VIEW_PROMO->value;

render($page_content); // $page_content contient déjà le chemin de la vue


?>