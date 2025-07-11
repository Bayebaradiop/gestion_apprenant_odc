<?php
require_once __DIR__ . '/../../vendor/autoload.php'; 

require_once __DIR__ . '/../enums/vers_page.php';
require_once __DIR__ . '/../enums/model.enum.php';
require_once __DIR__ . '/referenciel.controller.php';

use App\Enums\vers_page;
use App\Models\APPMETHODE;
use App\Models\JSONMETHODE;
use App\Models\REFMETHODE;
use App\ENUM\VALIDATOR\VALIDATORMETHODE;
use PhpOffice\PhpSpreadsheet\IOFactory;
require_once vers_page::MODEL->value;
require_once vers_page::SESSION_SERVICE->value;
require_once vers_page::APPRENANT_MODEL->value;
require_once vers_page::REF_MODEL->value; 
require_once vers_page::VALIDATOR_SERVICE->value;



global $apprenants, $ref_model;


function ajouter_apprenant(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        traiter_ajout_apprenant();
    } else {
        ajout_apprenant_vue();
    }
}




function filtrer_apprenants(array $apprenants, ?string $nomRecherche, ?int $referencielId, ?string $statut = null): array {
    return array_filter($apprenants, function ($apprenant) use ($nomRecherche, $referencielId, $statut) {
        $matchReferenciel = !$referencielId || ($apprenant['referenciel'] ?? null) == $referencielId;
        $matchNom = !$nomRecherche || stripos($apprenant['nom_complet'] ?? '', $nomRecherche) !== false;
        $matchStatut = !$statut || ($apprenant['statut'] ?? '') === $statut;
        return $matchReferenciel && $matchNom && $matchStatut;
    });
}




// function paginer(array $items, int $pageCourante, int $parPage): array {
//     $total = count($items);
//     $pages = max(1, ceil($total / $parPage));
//     $pageCourante = max(1, min($pageCourante, $pages)); 
//     $debut = ($pageCourante - 1) * $parPage;
//     $items_pagines = array_slice($items, $debut, $parPage);

//     return [
//         'items' => $items_pagines,
//         'total' => $total,
//         'pages' => $pages,
//         'pageCourante' => $pageCourante
//     ];
// }



function lister_apprenant(): void {
    global $apprenants;

    $nomRecherche = $_GET['search'] ?? null;
    $referencielId = isset($_GET['referenciel']) ? (int)$_GET['referenciel'] : null;
    $statut = $_GET['statut'] ?? null;
    $pageCourante = isset($_GET['pageCourante']) ? (int)$_GET['pageCourante'] : 1;
    $parPage = 5; 

    $apprenantsFiltres = filtrer_apprenants(
        $apprenants[APPMETHODE::GET_ALL->value]($nomRecherche, null),
        $nomRecherche,
        $referencielId,
        $statut
    );

    $pagination = paginer($apprenantsFiltres, $pageCourante, $parPage);

    $referenciels = charger_referenciels();

    render('apprenant/liste_apprenant', [
        'apprenants' => $pagination['items'],
        'referenciels' => $referenciels,
        'pagination' => $pagination
    ], layout: 'base.layout');
}



//en entente


function ajouter_en_attente(array $apprenant, string $cheminJson, string $motif): void {
    $contenu = json_decode(file_get_contents($cheminJson), true);

    // Ajouter motif de rejet
    $apprenant['motif_rejet'] = $motif;

    if (!isset($contenu['en_attente']) || !is_array($contenu['en_attente'])) {
        $contenu['en_attente'] = [];
    }

    $contenu['en_attente'][] = $apprenant;

    file_put_contents($cheminJson, json_encode($contenu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}



function lister_en_attente(): void {
    global $model_tab;

    $chemin = vers_page::DATA_JSON->value;
    $contenu = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);
    $en_attente = $contenu['en_attente'] ?? [];

    render('apprenant/entente', [
        'apprenants' => $en_attente
    ], layout: 'base.layout');
}


/// Fonction pour importer des apprenants depuis un fichier Excel
function importer_apprenants(): void {
    global $apprenants, $validator;

    if (fichier_excel_non_valide()) {
        enregistrer_message_erreur('Impossible d\'importer le fichier.');
        rediriger_vers_liste_apprenants();
        return;
    }

    $cheminFichier = $_FILES['import_excel']['tmp_name'];
    $lignes = charger_lignes_excel($cheminFichier);
    $cheminJson = vers_page::DATA_JSON->value;
    $referentielsValides = get_referentiels_valides($cheminJson);

    $apprenantsImportes = [];

    foreach (array_slice($lignes, 1, 300) as $index => $ligne) {
        if (empty(array_filter($ligne))) continue;

        $apprenant = valider_et_transformer_ligne($ligne, $index, $referentielsValides);

        if ($apprenant !== null) {
            $apprenantsImportes[] = $apprenant;

            // Envoi mail
            $email = $apprenant['login'];
            $login = $apprenant['login'];
            $password = 'password123';
            $envoiMail = envoyerEmailApprenant($email, $login, $password);
            if ($envoiMail !== true) {
                enregistrer_message_erreur("Échec d'envoi pour $email : $envoiMail");
            }
        }else {
            
            ajouter_en_attente(extraire_donnees_apprenant($ligne), $cheminJson, 'Erreur de validation ou référentiel invalide (ligne ' . ($index + 2) . ')');
        }
    }

    if (!empty($apprenantsImportes)) {
        $apprenants[APPMETHODE::IMPORTER->value]($apprenantsImportes, $cheminJson);
        enregistrer_message_succes('Importation réussie.');
    } else {
        enregistrer_message_erreur('Aucun apprenant valide importé.');
    }

    rediriger_vers_liste_apprenants();
}



// Vérifie si la promotion est en cours
function get_referentiels_valides(string $cheminJson): array {
    $data = json_decode(file_get_contents($cheminJson), true);
    $referentiels = [];

    foreach ($data['promotions'] ?? [] as $promo) {
        if (est_promo_en_cours($promo)) {
            foreach ($promo['referenciels'] as $refId) {
                $referentiels[] = (int)$refId;
            }
        }
    }

    return $referentiels;
}


/// Vérifie si la promotion est en cours
function valider_et_transformer_ligne(array $ligne, int $index, array $refValid): ?array {
    global $validator;

    $apprenant = extraire_donnees_apprenant($ligne);
    $apprenant['id'] = time() + rand(1, 999);
    $apprenant['password'] = password_hash('password123', PASSWORD_DEFAULT);

    if (!in_array((int)$apprenant['referenciel'], $refValid, true)) {
        stocker_session('errors', [ "referenciel.invalide ligne " . ($index + 2) ]);
        return null;
    }

    $erreurs = $validator[VALIDATORMETHODE::APPRENANT->value]($apprenant);
    if (!empty($erreurs)) {
        foreach ($erreurs as $champ => $cleErreur) {
            stocker_session('errors', [ "$cleErreur ligne " . ($index + 2) ]);
        }
        return null;
    }

    return $apprenant;
}




/**
 * Charger les lignes d'un fichier Excel
 */
function charger_lignes_excel(string $cheminFichier): array {
    try {
        $spreadsheet = IOFactory::load($cheminFichier);
        $sheet = $spreadsheet->getActiveSheet();
        return $sheet->toArray();
    } catch (Exception $e) {
        enregistrer_message_erreur('Erreur lors de la lecture du fichier Excel : ' . $e->getMessage());
        rediriger_vers_liste_apprenants();
        exit;
    }
}






/**
 * Vérifie si un fichier Excel est soumis
 */
function fichier_excel_non_valide(): bool {
    return !isset($_FILES['import_excel']) || $_FILES['import_excel']['error'] !== UPLOAD_ERR_OK;
}

/**
 * Extraire les données d'un apprenant depuis une ligne Excel
 */
function extraire_donnees_apprenant(array $ligne): array {
    return [
        'matricule' => generer_matricule(),
        'nom_complet' => $ligne[0] ?? '',
        'date_naissance' => $ligne[1] ?? '',
        'lieu_naissance' => $ligne[2] ?? '',
        'adresse' => $ligne[3] ?? '',
        'login' => $ligne[4] ?? '',
        'telephone' => $ligne[5] ?? '',
        'document' => $ligne[6] ?? '',
        'tuteur_nom' => $ligne[7] ?? '',
        'lien_parente' => $ligne[8] ?? '',
        'tuteur_adresse' => $ligne[9] ?? '',
        'tuteur_telephone' => $ligne[10] ?? '',
        'referenciel' => (int)($ligne[11] ?? 0),
        'statut' => 'Retenu',
        'profil' => 'Apprenant',
        'changer'=> 'false'
    ];
}


/// Enregistre un message d'erreur dans la session
function enregistrer_message_erreur(string $message): void {
    stocker_session('errors', [$message]);
}



/// Enregistre un message de succès dans la session
function enregistrer_message_succes(string $message): void {
    stocker_session('success', $message);
}

/**
 * Redirige vers la page liste_apprenant
 */
function rediriger_vers_liste_apprenants(): void {
    redirect_to_route('index.php', ['page' => 'liste_apprenant']);
}



global $apprenants;

/**
 * Afficher la page ajout apprenant
 */
function ajout_apprenant_vue(): void {
    global $model_tab;

    $matricule = generer_matricule();
    $referenciels = charger_referenciels();

    render('apprenant/ajouter_apprenant', [
        'matricule' => $matricule,
        'referenciels' => $referenciels
    ], layout: 'base.layout');
}


/**
 * Traiter ajout apprenant (POST)
 */
function traiter_ajout_apprenant(): void {
    global $apprenants, $validator;

    demarrer_session();

    $data = [
        'matricule' => trim($_POST['matricule'] ?? ''),
        'nom_complet' => trim($_POST['nom_complet'] ?? ''),
        'date_naissance' => trim($_POST['date_naissance'] ?? ''),
        'lieu_naissance' => trim($_POST['lieu_naissance'] ?? ''),
        'adresse' => trim($_POST['adresse'] ?? ''),
        'login' => trim($_POST['login'] ?? ''),
        'telephone' => trim($_POST['telephone'] ?? ''),
        'referenciel' => $_POST['referenciel'] ?? '',
        'photo' => $_FILES['document'] ?? null,
        'tuteur_nom' => trim($_POST['tuteur_nom'] ?? ''),
        'lien_parente' => trim($_POST['lien_parente'] ?? ''),
        'tuteur_adresse' => trim($_POST['tuteur_adresse'] ?? ''),
        'tuteur_telephone' => trim($_POST['tuteur_telephone'] ?? ''),
        'document' => $_FILES['document'] ?? null
    ];

    
    $errors = $validator[VALIDATORMETHODE::APPRENANT->value]($data);

    if (!empty($errors)) {
        stocker_session('errors', $errors);
        stocker_session('old_inputs', $data);
        redirect_to_route('index.php', ['page' => 'ajouter_apprenant']);
        exit;
    }

    

    $cheminJson = vers_page::DATA_JSON->value;
    $nouvelApprenant = creer_donnees_apprenant($data);

    $apprenants[APPMETHODE::AJOUTER->value]($nouvelApprenant, $cheminJson);

   
    
    $login = $nouvelApprenant['login'];
    $password = 'password123';
    $envoiMail = envoyerEmailApprenant($login, $login, $password);

    if ($envoiMail !== true) {
        enregistrer_message_erreur("Échec d'envoi pour $login : $envoiMail");
    }

    enregistrer_message_succes('Apprenant ajouté avec succès.');
    redirect_to_route('index.php', ['page' => 'liste_apprenant']);
}

/**
 * Générer un matricule automatique
 */

 function charger_referenciels(): array {
    global $model_tab;

    $chemin = vers_page::DATA_JSON->value;
    $contenu = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

    $referenciels = $contenu['referenciel'] ?? [];
    $promotions = $contenu['promotions'] ?? [];

    $idsRefsActives = [];

    foreach ($promotions as $promo) {
        if (est_promo_en_cours($promo)) {
            foreach ($promo['referenciels'] as $idRef) {
                $idsRefsActives[] = (int)$idRef;
            }
        }
    }

    return array_values(array_filter($referenciels, fn($ref) => in_array((int)$ref['id'], $idsRefsActives)));
}




/**
 * Préparer les données d'un nouvel apprenant
 */
function creer_donnees_apprenant(array $post): array {
    return [
        'matricule' => $post['matricule'],
        'nom_complet' => $post['nom_complet'],
        'date_naissance' => $post['date_naissance'],
        'lieu_naissance' => $post['lieu_naissance'],
        'adresse' => $post['adresse'],
        'login' => $post['login'],
        'telephone' => $post['telephone'],
        'referenciel' => (int) $post['referenciel'],
        'photo' => $post['photo']['name'] ?? '', // ou chemin si tu veux upload
        'statut' => 'Retenu',
        'profil' => 'Apprenant',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'id' => time() + rand(1, 999),
        'changer'=> 'false',
        'tuteur_nom' => $post['tuteur_nom'] ?? '',
        'lien_parente' => $post['lien_parente'] ?? '',
        'tuteur_adresse' => $post['tuteur_adresse'] ?? '',
        'tuteur_telephone' => $post['tuteur_telephone'] ?? '',
        'document' => $post['document']['name'] ?? '', // ou chemin si tu veux upload 
    ];
    envoyerEmailApprenant($post['login'], $post['login'],'password123');


}



function afficher_detail_apprenant(): void {
    global $apprenants, $model_tab;

    $id = $_GET['id'] ?? null;

    if (!$id) {
        enregistrer_message_erreur('ID apprenant manquant.');
        redirect_to_route('index.php', ['page' => 'liste_apprenant']);
        exit;
    }

    $apprenant = null;
    foreach ($apprenants[APPMETHODE::GET_ALL->value](null, null) as $a) {
        if (($a['id'] ?? '') == $id) {
            $apprenant = $a;
            break;
        }
    }

    if (!$apprenant) {
        enregistrer_message_erreur('Apprenant introuvable.');
        redirect_to_route('index.php', ['page' => 'liste_apprenant']);
        exit;
    }

    $referenciels = charger_referenciels();

    render('apprenant/details', [
        'apprenant' => $apprenant,
        'referenciels' => $referenciels
    ], layout: 'base.layout');
}



function afficher_formulaire_correction_en_attente(): void {
    $matricule = $_GET['matricule'] ?? null;
    if (!$matricule) {
        enregistrer_message_erreur("Identifiant manquant.");
        redirect_to_route('index.php', ['page' => 'entente']);
        exit;
    }

    global $model_tab;
    $chemin = vers_page::DATA_JSON->value;
    $contenu = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);
    $en_attente = $contenu['en_attente'] ?? [];

    $apprenant = array_filter($en_attente, fn($a) => $a['matricule'] == $matricule);
    if (empty($apprenant)) {
        enregistrer_message_erreur("Apprenant introuvable.");
        redirect_to_route('index.php', ['page' => 'entente']);
        exit;
    }

    $apprenant = array_values($apprenant)[0]; // Récupère le seul élément
    $referenciels = charger_referenciels();
    $errors = recuperer_session('errors', []);

    render('apprenant/modifier_entente', [
        'apprenant' => $apprenant,
        'referenciels' => $referenciels,
        'errors' => $errors
    ], layout: 'base.layout');
}






function traiter_modification_en_attente(): void {
    global $apprenants, $validator, $model_tab;

    $data = $_POST;

    $errors = $validator[VALIDATORMETHODE::APPRENANT->value]($data);

    if (!empty($errors)) {
        stocker_session('errors', $errors);
        redirect_to_route('index.php', ['page' => 'modifier_entente', 'matricule' => $data['matricule']]);
        exit;
    }

    $chemin = vers_page::DATA_JSON->value;
    $contenu = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

    // Supprimer l'apprenant correspondant via le matricule
    $contenu['en_attente'] = array_values(array_filter(
        $contenu['en_attente'],
        fn($a) => $a['matricule'] !== $data['matricule']
    ));

    // Ajout des champs obligatoires
    $data['id'] = time() + rand(1, 999);
    $data['password'] = password_hash('password123', PASSWORD_DEFAULT);
    $data['statut'] = 'Retenu';
    $data['profil'] = 'Apprenant';

    $contenu['utilisateurs'][] = $data;

    $model_tab[JSONMETHODE::ARRAYTOJSON->value]($contenu, $chemin);

    enregistrer_message_succes("Apprenant approuvé avec succès.");
    redirect_to_route('index.php', ['page' => 'liste_apprenant']);
}




function envoyerEmailApprenant($to, $login, $password) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bayebara2000@gmail.com'; 
        $mail->Password   = 'qtib crvw qfgj hrvz'; 
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('bayebara2000@gmail.com', 'sonatel_academy');
        $mail->addAddress($to);

        $mail->isHTML(false);
        $mail->Subject = 'Bienvenue sur la plateforme ODC-SENEGAL';
        $mail->Body    = "Bonjour,\n\nVotre compte a été créé avec succès.\nLogin : $login\nMot de passe : $password\n\nMerci.";

        $mail->send();
        return true;
    } catch (\Exception $e) {
        return "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
    }
}




?>
