<?php
require_once __DIR__ . '/../enums/vers_page.php';
use App\Enums\vers_page;
require_once vers_page::SESSION_SERVICE->value;
require_once vers_page::CONTROLLER->value;

demarrer_session();

if (!session_existe('user')) {
    redirect_to_route('index.php', ['page' => 'login']);
    exit;
}

$page = $_GET['content'] ?? 'liste_promo';

// Liste sécurisée des pages possibles
$pages_valides = [
    'liste_promo' => vers_page::VIEW_PROMO->value,
    //'ajouter_promo' => vers_page::AJOUTER_PROMO_VIEW->value,
    // ajoute d’autres si nécessaire
];

// On sécurise pour éviter l’inclusion arbitraire
$page_content = $pages_valides[$page] ?? vers_page::VIEW_PROMO->value;

render($page_content); // $page_content contient déjà le chemin de la vue


?>