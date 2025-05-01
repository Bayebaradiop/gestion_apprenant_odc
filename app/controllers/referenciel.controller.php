<?php
require_once __DIR__ . '/../enums/chemin_page.php';
require_once __DIR__ . '/../models/ref.model.php';
require_once __DIR__ . '/../models/model.php';
require_once __DIR__ . '/../services/session.service.php';
require_once __DIR__ . '/../services/validator.service.php';

use App\Enums\CheminPage;
use App\Models\REFMETHODE;
use App\ENUM\VALIDATOR\VALIDATORMETHODE;
use App\Models\JSONMETHODE;
demarrer_session();



/** --- ROUTAGE --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        match ($_POST['action']) {
            'ajouter' => ajouter_referenciel(),
            'affecter' => affecter_referenciel_a_promo_active(),
            'desaffecter' => desaffecter_referenciel_de_promo_active(),
            default => null,
        };
    }
}



/** --- AFFICHER PAGE D'AJOUT REFERENTIEL --- */
function afficher_page_add_ref(): void {
    render('referenciel/add_referentiel');
}

/** --- AFFICHER REFERENTIELS (PROMO ACTIVE) --- */
function afficher_referentiels(): void {
    global $ref_model, $model_tab;

    $chemin = CheminPage::DATA_JSON->value;
    $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

    $promo_active = get_promo_active($data['promotions'] ?? []);
    $referentiels = [];

    if ($promo_active && !empty($promo_active['referenciels'])) {
        $all_ref = $ref_model[REFMETHODE::GET_ALL->value]();
        $referentiels = get_referentiels_actifs($all_ref, $promo_active['referenciels']);
    }

    if (!empty($_GET['search'] ?? '')) {
        $referentiels = filtrer_referentiels_par_nom($referentiels, trim($_GET['search']));
    }

    $parPage = 6;
    $pageCourante = (int) ($_GET['p'] ?? 1);
    $pagination = paginer($referentiels, $pageCourante, $parPage);

    render('referenciel/referenciel', [
        'referentiels' => $pagination['items'],
        'page' => $pagination['pageCourante'],
        'total' => $pagination['pages'],
        'debut' => $pagination['debut'],
        'parPage' => $parPage
    ]);
}

/** --- AFFICHER TOUS LES REFERENTIELS --- */
function afficher_tous_referentiels(): void {
    global $ref_model;

    $referentiels = $ref_model[REFMETHODE::GET_ALL->value]();

    if (!empty($_GET['search'] ?? '')) {
        $referentiels = filtrer_referentiels_par_nom($referentiels, trim($_GET['search']));
    }

    $parPage = 2;
    $pageCourante = (int) ($_GET['p'] ?? 1);
    $pagination = paginer($referentiels, $pageCourante, $parPage);

    render('referenciel/all_referenciel', [
        'referentiels' => $pagination['items'],
        'page' => $pagination['pageCourante'],
        'total' => $pagination['pages'],
        'debut' => $pagination['debut'],
        'parPage' => $parPage,
        'searchTerm' => $_GET['search'] ?? '',
        'nombreTotal' => $pagination['total']
    ]);
}

/** --- AJOUTER UN NOUVEAU REFERENTIEL --- */
function ajouter_referenciel(): void {
    global $ref_model, $validator;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'ajouter') {
        redirect_to_route('index.php', ['page' => 'all_referenciel']);
        exit;
    }

    $referentielsExistants = $ref_model[REFMETHODE::GET_ALL->value]();

    $data = [
        'nom' => trim($_POST['nom'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'capacite' => trim($_POST['capacite'] ?? ''),
        'sessions' => trim($_POST['sessions'] ?? ''),
        'photo' => $_FILES['photo'] ?? null,
    ];

    $erreurs = $validator[VALIDATORMETHODE::REF->value]($data, $referentielsExistants);

    if (!empty($erreurs)) {
        stocker_session('errors', $erreurs);
        stocker_session('old_inputs', $data);
        redirect_to_route('index.php', ['page' => 'add_referentiel']);
        exit;
    }

    $cheminPhoto = gerer_upload_photo($data['photo']) ?? 'assets/images/referentiel/default.png';

    $nouveau_ref = [
        'id' => time(),
        'nom' => $data['nom'],
        'description' => $data['description'],
        'capacite' => (int) $data['capacite'],
        'sessions' => (int) $data['sessions'],
        'photo' => $cheminPhoto,
        'modules' => 0,
        'apprenants' => 0
    ];

    $ref_model[REFMETHODE::AJOUTER->value]($nouveau_ref);

    stocker_session('success', 'Référentiel ajouté avec succès.');
    redirect_to_route('index.php', ['page' => 'all_referenciel']);
}


/** --- FONCTIONS D'AFFECTATION / DÉSAFFECTATION --- */
function afficher_referentiels_promo(): void {
    global $ref_model, $model_tab;

    $chemin = CheminPage::DATA_JSON->value;
    $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

    $promo_active = get_promo_active($data['promotions'] ?? []);
    $promoActiveEnCours = $promo_active ? est_promo_en_cours($promo_active) : false;

    $referentiels_actifs = [];
    if ($promo_active && !empty($promo_active['referenciels'])) {
        $all_ref = $ref_model[REFMETHODE::GET_ALL->value]();
        $referentiels_actifs = get_referentiels_actifs($all_ref, $promo_active['referenciels']);
    }

    $referentiels_non_affectes = $ref_model[REFMETHODE::GET_NON_AFFECTES->value]();

    render('referenciel/affecter_ref_promo', [
        'referentiels_actifs' => $referentiels_actifs,
        'referentiels_non_affectes' => $referentiels_non_affectes,
        'promoActiveEnCours' => $promoActiveEnCours
    ]);
}

function affecter_referenciel_a_promo_active(): void {
    global $ref_model, $model_tab;

    $ref_id = (int) ($_POST['referenciel_id'] ?? 0);
    $chemin = CheminPage::DATA_JSON->value;
    $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

    $promo_active = get_promo_active($data['promotions'] ?? []);

    if (!$promo_active || !est_promo_en_cours($promo_active)) {
        stocker_session('errors', ["Impossible d'affecter car pas de promotion active."]);
        redirect_to_affectation();
    }

    $ref_model[REFMETHODE::AFFECTER_REF_PROMO_ACTIVE->value]($ref_id);
    stocker_session('success', "Référentiel affecté avec succès !");
    redirect_to_affectation();
}


function desaffecter_referenciel_de_promo_active(): void {
    global $ref_model;

    $ref_id = (int) ($_POST['referenciel_id'] ?? 0);
    $promo_active = get_promo_active_from_json();
    $referentiels = get_all_referentiels();
    $ref_courant = trouver_referenciel_par_id($referentiels, $ref_id);

    if (!valider_droits_desaffectation($promo_active, $ref_courant)) {
        redirect_to_affectation();
    }

    $ref_model[REFMETHODE::DESAFFECTER->value]($ref_id);
    stocker_session('success', "Référentiel désaffecté avec succès !");
    redirect_to_affectation();
}



/** --- PETITES FONCTIONS UTILITAIRES --- */
function redirect_to_affectation(): void {
    redirect_to_route('index.php', ['page' => 'affecter_ref_promo']);
    exit;
}


function get_promo_active(array $promotions): ?array {
    foreach ($promotions as $promo) {
        if (($promo['statut'] ?? '') === 'Active') {
            return $promo;
        }
    }
    return null;
}

function get_referentiels_actifs(array $referentiels, array $ids_referenciels): array {
    return array_filter($referentiels, fn($ref) => in_array($ref['id'], $ids_referenciels));
}

function filtrer_referentiels_par_nom(array $referentiels, string $searchTerm): array {
    return array_filter($referentiels, fn($ref) => stripos($ref['nom'], $searchTerm) !== false);
}

function get_promo_active_from_json(): ?array {
    global $model_tab;

    $chemin = CheminPage::DATA_JSON->value;
    $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

    return get_promo_active($data['promotions'] ?? []);
}

function get_all_referentiels(): array {
    global $ref_model;
    return $ref_model[REFMETHODE::GET_ALL->value]();
}


function trouver_referenciel_par_id(array $referentiels, int $ref_id): ?array {
    foreach ($referentiels as $ref) {
        if ($ref['id'] === $ref_id) {
            return $ref;
        }
    }
    return null;
}

function valider_droits_desaffectation(?array $promo_active, ?array $ref): bool {
    if (!$promo_active || !est_promo_en_cours($promo_active)) {
        stocker_session('errors', ["Promotion non active ou terminée."]);
        return false;
    }
    if (!$ref) {
        stocker_session('errors', ["Référentiel introuvable."]);
        return false;
    }
    if (($ref['apprenants'] ?? 0) > 0) {
        stocker_session('errors', ["Impossible de désaffecter un référentiel avec des apprenants."]);
        return false;
    }
    return true;
}


function est_promo_en_cours(array $promo): bool {
    $aujourdhui = new DateTime();
    $debut = new DateTime($promo['dateDebut']);
    $fin = new DateTime($promo['dateFin']);

    $anneeActuelle = (int) $aujourdhui->format('Y');
    $anneeDebut = (int) $debut->format('Y');
    $anneeFin = (int) $fin->format('Y');

    return (
        $promo['statut'] === 'Active' &&
        $aujourdhui < $fin &&
        $anneeActuelle === $anneeDebut &&
        $anneeDebut + 1 === $anneeFin
    );
}
