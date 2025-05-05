<?php

require_once __DIR__ . '/../enums/vers_page.php';
use App\Enums\vers_page;

require_once vers_page::MODEL->value;
require_once vers_page::MESSAGE_ENUM->value;
require_once vers_page::ERREUR_ENUM->value;
require_once vers_page::SESSION_SERVICE->value;
require_once vers_page::VALIDATOR_SERVICE->value;
require_once vers_page::REF_MODEL->value;
require_once vers_page::MODEL_ENUM->value;
require_once vers_page::PROMO_MODEL->value;

use App\ENUM\ERREUR\ErreurEnum;
use App\Models\PROMOMETHODE;
use App\Models\JSONMETHODE;
use App\ENUM\MESSAGE\MSGENUM;
use App\ENUM\VALIDATOR\VALIDATORMETHODE;
use App\Models\REFMETHODE;



function afficher_page_add_promo() {
    global $ref_model;

    render('promo/add_promo', [
        'referentiels' => $ref_model[REFMETHODE::GET_ALL->value]()
    ]);
}

//Afficher la liste des promotions

function afficher_promotions_generique(string $vue, int $parPage): void {
    $nomRecherche = $_GET['search'] ?? null;
    $filtreStatut = $_GET['filtre'] ?? null;
    $pageCourante = get_page_courante();
    $limit = $parPage;

    $liste_promos = get_promotions_filtrees($nomRecherche, $filtreStatut);
    $referentiels = get_tous_les_referentiels();

    $promo_active = extraire_promo_active($liste_promos);
    $promos_inactives = extraire_promos_inactives($liste_promos);

    $pagination = paginer($promos_inactives, $pageCourante, $limit);
    $promotions_finales = array_merge($promo_active, $pagination['items']);

    $nbRefPromoActive = get_nb_referentiels_promo_active();
    $promoActiveName = get_promo_active_name();
    $totalPromotions = get_total_promotions();

    render($vue, [
        "promotions" => $promotions_finales,
        "referentiels" => $referentiels,
        "page" => $pagination['pageCourante'],
        "pages" => $pagination['pages'],
        "limit" => $limit,
        "total" => $pagination['total'],
        "nbRefPromoActive" => $nbRefPromoActive, 
        "promoActiveName" => $promoActiveName,
        "totalPromotions" => $totalPromotions, 
    ]);
}



function afficher_promotions(): void {
    afficher_promotions_generique("promo/promo", 5);
}


function afficher_promotions_en_table(): void {
    afficher_promotions_generique("promo/liste_promo", 5);
}



//ajouter une promotion

function traiter_creation_promotion(): void {
    global $validator;

    demarrer_session();

    $data = [
        'nom_promo' => trim($_POST['nom_promo'] ?? ''),
        'date_debut' => trim($_POST['date_debut'] ?? ''),
        'date_fin' => trim($_POST['date_fin'] ?? ''),
        'photo' => $_FILES['photo'] ?? null,
        'referenciels' => $_POST['referenciels'] ?? [],
    ];

    $erreurs = $validator[VALIDATORMETHODE::VALID_GENERAL->value]($data);

    if (!empty($erreurs)) {
        stocker_session('errors', $erreurs);
        stocker_session('old_inputs', $data);
        redirect_to_route('index.php', ['page' => 'add_promo']);
        exit;
    }

    $cheminFichier = vers_page::DATA_JSON->value;
    $donneesExistantes = charger_promotions_existantes($cheminFichier);

    $cheminPhoto = $data['photo']['name'] ?? '';
    $nouvellePromo = creer_donnees_promotion($data, $donneesExistantes, $cheminPhoto);

    $donneesExistantes['promotions'][] = $nouvellePromo;
    sauvegarder_promotions($cheminFichier, $donneesExistantes);

    redirect_to_route('index.php', ['page' => 'liste_promo']);
}


function charger_promotions_existantes(string $chemin): array {
    global $model_tab;
    return $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);
}



function sauvegarder_promotions(string $chemin, array $data): void {
    global $model_tab;
    $model_tab[JSONMETHODE::ARRAYTOJSON->value]($data, $chemin);
}


function creer_donnees_promotion(array $post, array $donneesExistantes, string $cheminPhoto): array {
    $promotions = $donneesExistantes['promotions'] ?? [];
    $nouvelId = getNextPromoId($promotions);

    $referenciels = isset($post['referenciels']) ? array_map('intval', $post['referenciels']) : [];

    return [
        "id" => $nouvelId,
        "nom" => $post['nom_promo'],
        "dateDebut" => $post['date_debut'],
        "dateFin" => $post['date_fin'],
        "referenciels" => $referenciels,
        "photo" => $cheminPhoto,
        "statut" => "Inactive",
        "nbrApprenant" => 0,
    ];
}

//fin




//activer une promotion
function activer_promotion_et_rediriger(int $idPromo, string $redirectPage): void {
    global $promos;

    $cheminFichier = vers_page::DATA_JSON->value;
    $promos[PROMOMETHODE::ACTIVER_PROMO->value]($idPromo, $cheminFichier);

    redirect_to_route('index.php', ['page' => $redirectPage]);
}



function traiter_activation_promotion(): void {
    if (isset($_GET['activer_promo'])) {
        activer_promotion_et_rediriger((int) $_GET['activer_promo'], 'liste_promo');
    }
}

function traiter_activation_promotion_liste(): void {
    if (isset($_GET['activer_promo_liste'])) {
        activer_promotion_et_rediriger((int) $_GET['activer_promo_liste'], 'liste_table_promo');
    }
}

//f





// Fonctions utilitaires

function get_promotions_filtrees(?string $search = null, ?string $filtre = null): array {
    global $promos;
    return $promos[PROMOMETHODE::GET_ALL->value]($search, $filtre);
}

function get_tous_les_referentiels(): array {
    global $ref_model;
    return $ref_model[REFMETHODE::GET_ALL->value]();
}

function extraire_promo_active(array $promos): array {
    return array_filter($promos, fn($p) => $p['statut'] === 'Active');
}

function extraire_promos_inactives(array $promos): array {
    return array_filter($promos, fn($p) => $p['statut'] !== 'Active');
}

function get_page_courante(): int {
    return isset($_GET['p']) && is_numeric($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
}



// function get_limit_from_request(): int {
//     return isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
// }

// function get_page_from_request(): int {
//     return isset($_GET['p']) ? (int)$_GET['p'] : 1;
// }





function get_promo_active_name(): ?string {
    global $model_tab;

    $chemin = \App\Enums\vers_page::DATA_JSON->value;
    $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

    if (!empty($data['promotions'])) {
        foreach ($data['promotions'] as $promo) {
            if ($promo['statut'] === 'Active') {
                return $promo['nom'];
            }
        }
    }
    return null; 
}



function get_nb_referentiels_promo_active(): int {
    global $model_tab;

    $chemin = \App\Enums\vers_page::DATA_JSON->value;
    $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

    if (!empty($data['promotions'])) {
        foreach ($data['promotions'] as $promo) {
            if ($promo['statut'] === 'Active') {
                return isset($promo['referenciels']) ? count($promo['referenciels']) : 0;
            }
        }
    }
    return 0;
}



function get_total_promotions(): int {
    global $model_tab;

    $chemin = \App\Enums\vers_page::DATA_JSON->value;
    $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

    return isset($data['promotions']) ? count($data['promotions']) : 0;
}
