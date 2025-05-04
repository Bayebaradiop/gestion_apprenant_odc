<?php
require_once __DIR__ . '/../enums/vers_page.php';
use App\Enums\CheminPage;
require_once CheminPage::CONTROLLER->value;
require_once CheminPage::MODEL->value;
// Définir la page par défaut
$page = $_GET['page'] ?? 'login';
// Résolution des routes
match ($page) {
    'login', 'logout' => (function () {
        require_once CheminPage::AUTH_CONTROLLER->value;
        voir_page_login();
    })(),

    'resetPassword' => (function () {
        require_once CheminPage::AUTH_CONTROLLER->value;
    })(),
    
    'liste_promo' => (function () {
        require_once CheminPage::PROMO_CONTROLLER->value;
        afficher_promotions();
    })(),
    
    'liste_table_promo' => (function () {
        require_once CheminPage::PROMO_CONTROLLER->value;
        afficher_promotions_en_table();
    })(),

    'liste_apprenant' => (function () {
        require_once CheminPage::APPRENANT_CONTROLLER->value;
        lister_apprenant();
    })(),

   'ajouter_apprenant' => (function () {
        require_once CheminPage::APPRENANT_CONTROLLER->value;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            traiter_ajout_apprenant();
        } else {
            ajout_apprenant_vue();
        }
    })(),

    'detail_apprenant' => (function () {
        require_once CheminPage::APPRENANT_CONTROLLER->value;
        afficher_detail_apprenant();
    })(),

    'import_apprenants' => (function () {
        require_once CheminPage::APPRENANT_CONTROLLER->value;
        importer_apprenants();
    })(),

    'layout' => (function () {
        require_once CheminPage::LAYOUT_CONTROLLER->value;
    })(),

    'referenciel' => (function () {
        require_once CheminPage::REFERENCIEL_CONTROLLER->value;
        afficher_referentiels();
    })(),

    'all_referenciel' => (function () {
        require_once CheminPage::REFERENCIEL_CONTROLLER->value;
        afficher_tous_referentiels();
    })(),

    'add_referentiel' => (function () {
        require_once CheminPage::REFERENCIEL_CONTROLLER->value;
        afficher_page_add_ref();
    })(),

    'add_promo' => (function () {
        require_once CheminPage::PROMO_CONTROLLER->value;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            traiter_creation_promotion();
        } else {
            afficher_page_add_promo();
        }
    })(),

    'activer_promo' => (function () {
        require_once CheminPage::PROMO_CONTROLLER->value;
        traiter_activation_promotion();
    })(),

    'affecter_ref_promo' => (function () {
        require_once CheminPage::REFERENCIEL_CONTROLLER->value;
        afficher_referentiels_promo();
    })(),

    'activer_promo_liste' => (function () {
        require_once CheminPage::PROMO_CONTROLLER->value;
        traiter_activation_promotion_liste();
    })(),

    default => (function () use ($page) {
        require_once CheminPage::ERROR_CONTROLLER->value;
    })()
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    match ($_POST['action'] ?? '') {
        'ajouter' => (function () {
            require_once CheminPage::REFERENCIEL_CONTROLLER->value;
            ajouter_referenciel();
        })(),
        'affecter' => (function () {
            require_once CheminPage::REFERENCIEL_CONTROLLER->value;
            affecter_referenciel_a_promo_active();
        })(),
        'desaffecter' => (function () {
            require_once CheminPage::REFERENCIEL_CONTROLLER->value;
            desaffecter_referenciel_de_promo_active();
        })(),
        default => null,
    };
}






